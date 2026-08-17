<?php

namespace App\Mail;

use App\Models\Tenant\Order;
use App\Services\InvoiceService;
use App\Services\TenantManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderPaidMail extends Mailable implements ShouldQueue
{
    use Queueable; // Removed SerializesModels to prevent Queue database connection deserialization errors

    public string $orderId;
    public ?string $tenantId;

    protected ?Order $orderInstance = null;

    /**
     * Create a new message instance.
     */
    public function __construct(string $orderId, ?string $tenantId = null)
    {
        $this->orderId = $orderId;
        $this->tenantId = $tenantId;
    }

    /**
     * Get the order instance dynamically, switching database connection context if needed.
     */
    protected function getOrder(): Order
    {
        if (!$this->orderInstance) {
            if ($this->tenantId) {
                $tenant = \App\Models\Landlord\Tenant::findOrFail($this->tenantId);
                app(TenantManager::class)->setTenant($tenant);
            }
            $this->orderInstance = Order::with('items')->findOrFail($this->orderId);
        }

        return $this->orderInstance;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $order = $this->getOrder();
        return new Envelope(
            subject: 'Bestelling Bevestigd - ' . $order->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $order = $this->getOrder();
        $trackingUrl = null;

        if ($order->customer_id === null) {
            $tenant = $this->tenantId
                ? \App\Models\Landlord\Tenant::with('domains')->find($this->tenantId)
                : app(TenantManager::class)->getTenant();

            if ($tenant) {
                $tenant->loadMissing('domains');
                $subdomain = $tenant->slug;
                if ($subdomain) {
                    $trackingUrl = route('storefront.order.track', [
                        'tenant' => $subdomain,
                        'id' => $order->id,
                    ]);
                }
            }
        }

        return new Content(
            markdown: 'emails.orders.paid',
            with: [
                'order' => $order,
                'items' => $order->items,
                'trackingUrl' => $trackingUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $order = $this->getOrder();
        $invoiceService = app(InvoiceService::class);
        $pdfContent = $invoiceService->generate($order);

        return [
            Attachment::fromData(fn () => $pdfContent, "Factuur-{$order->order_number}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}

