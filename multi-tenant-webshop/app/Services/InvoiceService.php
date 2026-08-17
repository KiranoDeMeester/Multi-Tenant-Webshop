<?php

namespace App\Services;

use App\Models\Tenant\Order;
use App\Models\Tenant\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class InvoiceService
{
    /**
     * Generate and stream/download a PDF invoice for the given order.
     */
    public function downloadPdf(Order $order): Response
    {
        $order->load(['items.product', 'items.variation.attributeValues.attribute', 'customer']);

        $settings = [
            'company_name' => Setting::where('key', 'invoice_company_name')->first()?->value ?? config('app.name'),
            'address' => Setting::where('key', 'invoice_address')->first()?->value ?? 'Handelsstraat 1, 1000 Brussel',
            'vat_number' => Setting::where('key', 'invoice_vat_number')->first()?->value ?? 'BE 0123.456.789',
            'email' => Setting::where('key', 'invoice_email')->first()?->value ?? config('mail.from.address'),
            'footer_text' => Setting::where('key', 'invoice_footer_text')->first()?->value ?? 'Bedankt voor uw aankoop bij onze webshop!',
            'vat_percentage' => (float) (Setting::where('key', 'invoice_vat_percentage')->first()?->value ?? 21),
            'logo' => Setting::where('key', 'invoice_logo')->first()?->value,
        ];

        $invoiceNumber = 'INV-' . date('Y', strtotime($order->created_at)) . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);

        $totalCents = (int) $order->total_amount;
        $shippingCents = (int) ($order->shipping_amount ?? 0);
        $taxCents = (int) ($order->tax_amount ?? 0);
        $subtotalExclTaxCents = $totalCents - $shippingCents - $taxCents;

        $data = [
            'order' => $order,
            'invoiceNumber' => $invoiceNumber,
            'invoiceDate' => $order->created_at->format('d-m-Y'),
            'settings' => $settings,
            'subtotalExclTax' => $subtotalExclTaxCents / 100,
            'taxAmount' => $taxCents / 100,
            'shippingAmount' => $shippingCents / 100,
            'totalAmount' => $totalCents / 100,
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data)->setPaper('a4', 'portrait');

        return $pdf->download("factuur-{$order->order_number}.pdf");
    }

    /**
     * Generate binary PDF content for email attachments.
     */
    public function generate(Order $order): string
    {
        return $this->generatePdfContent($order);
    }

    /**
     * Generate binary PDF content for the given order.
     */
    public function generatePdfContent(Order $order): string
    {
        $order->load(['items.product', 'items.variation.attributeValues.attribute', 'customer']);

        $settings = [
            'company_name' => Setting::where('key', 'invoice_company_name')->first()?->value ?? config('app.name'),
            'address' => Setting::where('key', 'invoice_address')->first()?->value ?? 'Handelsstraat 1, 1000 Brussel',
            'vat_number' => Setting::where('key', 'invoice_vat_number')->first()?->value ?? 'BE 0123.456.789',
            'email' => Setting::where('key', 'invoice_email')->first()?->value ?? config('mail.from.address'),
            'footer_text' => Setting::where('key', 'invoice_footer_text')->first()?->value ?? 'Bedankt voor uw aankoop bij onze webshop!',
            'vat_percentage' => (float) (Setting::where('key', 'invoice_vat_percentage')->first()?->value ?? 21),
            'logo' => Setting::where('key', 'invoice_logo')->first()?->value,
        ];

        $invoiceNumber = 'INV-' . date('Y', strtotime($order->created_at)) . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);

        $totalCents = (int) $order->total_amount;
        $shippingCents = (int) ($order->shipping_amount ?? 0);
        $taxCents = (int) ($order->tax_amount ?? 0);
        $subtotalExclTaxCents = $totalCents - $shippingCents - $taxCents;

        $data = [
            'order' => $order,
            'invoiceNumber' => $invoiceNumber,
            'invoiceDate' => $order->created_at->format('d-m-Y'),
            'settings' => $settings,
            'subtotalExclTax' => $subtotalExclTaxCents / 100,
            'taxAmount' => $taxCents / 100,
            'shippingAmount' => $shippingCents / 100,
            'totalAmount' => $totalCents / 100,
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data)->setPaper('a4', 'portrait');

        return $pdf->output();
    }

    /**
     * Render the invoice as HTML string for previews or testing.
     */
    public function renderHtml(Order $order): string
    {
        $order->load(['items.product', 'items.variation.attributeValues.attribute', 'customer']);

        $settings = [
            'company_name' => Setting::where('key', 'invoice_company_name')->first()?->value ?? config('app.name'),
            'address' => Setting::where('key', 'invoice_address')->first()?->value ?? 'Handelsstraat 1, 1000 Brussel',
            'vat_number' => Setting::where('key', 'invoice_vat_number')->first()?->value ?? 'BE 0123.456.789',
            'email' => Setting::where('key', 'invoice_email')->first()?->value ?? config('mail.from.address'),
            'footer_text' => Setting::where('key', 'invoice_footer_text')->first()?->value ?? 'Bedankt voor uw aankoop bij onze webshop!',
            'vat_percentage' => (float) (Setting::where('key', 'invoice_vat_percentage')->first()?->value ?? 21),
            'logo' => Setting::where('key', 'invoice_logo')->first()?->value,
        ];

        $invoiceNumber = 'INV-' . date('Y', strtotime($order->created_at)) . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);

        $totalCents = (int) $order->total_amount;
        $shippingCents = (int) ($order->shipping_amount ?? 0);
        $taxCents = (int) ($order->tax_amount ?? 0);
        $subtotalExclTaxCents = $totalCents - $shippingCents - $taxCents;

        return view('pdf.invoice', [
            'order' => $order,
            'invoiceNumber' => $invoiceNumber,
            'invoiceDate' => $order->created_at->format('d-m-Y'),
            'settings' => $settings,
            'subtotalExclTax' => $subtotalExclTaxCents / 100,
            'taxAmount' => $taxCents / 100,
            'shippingAmount' => $shippingCents / 100,
            'totalAmount' => $totalCents / 100,
        ])->render();
    }
}
