<?php

namespace App\Mail;

use App\Models\Landlord\Tenant;
use App\Models\Tenant\Order;
use App\Services\TenantManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderCancelledMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public string $orderId;

    public ?string $tenantId;

    protected ?Order $orderInstance = null;

    public function __construct(string $orderId, ?string $tenantId = null)
    {
        $this->orderId = $orderId;
        $this->tenantId = $tenantId;
    }

    protected function getOrder(): Order
    {
        if (! $this->orderInstance) {
            if ($this->tenantId) {
                $tenant = Tenant::find($this->tenantId);
                if ($tenant) {
                    app(TenantManager::class)->setTenant($tenant);
                }
            }
            $this->orderInstance = Order::with('items')->findOrFail($this->orderId);
        }

        return $this->orderInstance;
    }

    public function envelope(): Envelope
    {
        $order = $this->getOrder();

        return new Envelope(
            subject: 'Bestelling Geannuleerd - '.$order->order_number,
        );
    }

    public function content(): Content
    {
        $order = $this->getOrder();

        return new Content(
            markdown: 'emails.orders.cancelled',
            with: [
                'order' => $order,
                'items' => $order->items,
            ],
        );
    }
}
