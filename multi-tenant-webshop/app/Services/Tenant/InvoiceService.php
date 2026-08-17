<?php

namespace App\Services\Tenant;

use App\Models\Tenant\Order;
use App\Models\Tenant\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    /**
     * Generate a PDF invoice for the given order.
     */
    public function generate(Order $order)
    {
        $settings = $this->getInvoiceSettings();

        $pdf = Pdf::loadView('emails.orders.invoice', [
            'order' => $order->load('items.product'),
            'settings' => $settings,
        ]);

        return $pdf->output();
    }

    /**
     * Get invoice-related settings with defaults.
     */
    protected function getInvoiceSettings(): array
    {
        return [
            'company_name' => Setting::where('key', 'invoice_company_name')->first()?->value ?? config('app.name'),
            'address' => Setting::where('key', 'invoice_address')->first()?->value ?? 'Adres niet geconfigureerd',
            'vat_number' => Setting::where('key', 'invoice_vat_number')->first()?->value ?? '',
            'email' => Setting::where('key', 'invoice_email')->first()?->value ?? config('mail.from.address'),
            'footer_text' => Setting::where('key', 'invoice_footer_text')->first()?->value ?? 'Bedankt voor uw bestelling!',
            'logo' => Setting::where('key', 'invoice_logo')->first()?->value, // Path in storage
        ];
    }
}
