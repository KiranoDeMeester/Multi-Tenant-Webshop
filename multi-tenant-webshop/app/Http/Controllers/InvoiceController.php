<?php

namespace App\Http\Controllers;

use App\Models\Tenant\Order;
use App\Services\InvoiceService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    use AuthorizesRequests;

    /**
     * Download invoice as customer.
     */
    public function downloadCustomerInvoice(Order $order, InvoiceService $invoiceService): Response
    {
        $this->authorize('view', $order);

        return $invoiceService->downloadPdf($order);
    }

    /**
     * Download invoice as merchant.
     */
    public function downloadMerchantInvoice(Order $order, InvoiceService $invoiceService): Response
    {
        $this->authorize('view', $order);

        return $invoiceService->downloadPdf($order);
    }
}
