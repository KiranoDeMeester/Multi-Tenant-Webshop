<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Factuur {{ $invoiceNumber }}</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        body {
            color: #1a1a1a;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 30px;
        }
        .header {
            width: 100%;
            margin-bottom: 35px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .company-name {
            font-size: 22px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            color: #000;
        }
        .invoice-title {
            text-align: right;
            font-size: 26px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -1px;
            color: #000;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .meta-col {
            width: 50%;
            vertical-align: top;
        }
        .section-label {
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
            margin-bottom: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f4f4f5;
            border-bottom: 2px solid #000;
            padding: 10px 12px;
            text-align: left;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e4e4e7;
        }
        .items-table .text-right {
            text-align: right;
        }
        .totals-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
            margin-bottom: 35px;
        }
        .totals-table td {
            padding: 6px 10px;
        }
        .totals-table .total-row {
            border-top: 2px solid #000;
            font-size: 14px;
            font-weight: 900;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e4e4e7;
            text-align: center;
            font-size: 10px;
            color: #71717a;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #dcfce7;
            color: #166534;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td style="vertical-align: top;">
                    <div class="company-name">{{ $settings['company_name'] }}</div>
                    <div style="color: #71717a; margin-top: 4px;">{{ $settings['address'] }}</div>
                    <div style="color: #71717a;">BTW: {{ $settings['vat_number'] }} | E: {{ $settings['email'] }}</div>
                </td>
                <td style="vertical-align: top; text-align: right;">
                    <div class="invoice-title">FACTUUR</div>
                    <div style="font-weight: bold; font-size: 13px; margin-top: 4px;">{{ $invoiceNumber }}</div>
                    <div style="color: #71717a;">Datum: {{ $invoiceDate }}</div>
                    <div style="margin-top: 5px;">
                        <span class="status-badge">{{ strtoupper($order->status) }}</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Meta Details (Customer & Order info) -->
    <table class="meta-table">
        <tr>
            <td class="meta-col">
                <div class="section-label">Factuuradres:</div>
                <div style="font-weight: bold; font-size: 13px;">
                    {{ $order->customer_details['name'] ?? $order->customer?->name ?? 'Gastklant' }}
                </div>
                @php
                    $billing = $order->customer_details['billing_address'] ?? $order->customer_details['shipping_address'] ?? null;
                @endphp
                @if($billing)
                    <div>{{ $billing['street'] ?? '' }} {{ $billing['house_number'] ?? '' }}</div>
                    <div>{{ $billing['postal_code'] ?? '' }} {{ $billing['city'] ?? '' }}</div>
                    <div>{{ $billing['country'] ?? 'België' }}</div>
                @endif
                <div style="color: #71717a; margin-top: 4px;">
                    {{ $order->customer_details['email'] ?? $order->customer?->email ?? '' }}
                </div>
            </td>
            <td class="meta-col" style="text-align: right;">
                <div class="section-label">Bestelgegevens:</div>
                <div><strong>Bestelnummer:</strong> {{ $order->order_number }}</div>
                <div><strong>Betaalmethode:</strong> Stripe Checkout</div>
                @if($order->stripe_session_id)
                    <div style="font-size: 10px; color: #71717a;"><strong>Transactie:</strong> {{ substr($order->stripe_session_id, 0, 20) }}...</div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Line Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Omschrijving</th>
                <th class="text-right" style="width: 15%;">Aantal</th>
                <th class="text-right" style="width: 15%;">Prijs excl. BTW</th>
                <th class="text-right" style="width: 20%;">Totaal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                @php
                    $itemUnitPriceExcl = ($item->price / 100) / (1 + ($settings['vat_percentage'] / 100));
                    $itemTotalIncl = ($item->price * $item->quantity) / 100;
                @endphp
                <tr>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->sku)
                            <div style="font-size: 10px; color: #71717a;">SKU: {{ $item->sku }}</div>
                        @endif
                        @if(!empty($item->options))
                            <div style="font-size: 10px; color: #4f46e5;">
                                @foreach($item->options as $optK => $optV)
                                    {{ $optK }}: {{ $optV }}
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">€{{ number_format($itemUnitPriceExcl, 2, ',', '.') }}</td>
                    <td class="text-right"><strong>€{{ number_format($itemTotalIncl, 2, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals Table -->
    <table class="totals-table">
        <tr>
            <td style="color: #71717a;">Subtotaal (excl. BTW):</td>
            <td class="text-right">€{{ number_format($subtotalExclTax, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="color: #71717a;">BTW ({{ $settings['vat_percentage'] }}%):</td>
            <td class="text-right">€{{ number_format($taxAmount, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="color: #71717a;">Verzendkosten:</td>
            <td class="text-right">
                @if($shippingAmount > 0)
                    €{{ number_format($shippingAmount, 2, ',', '.') }}
                @else
                    GRATIS
                @endif
            </td>
        </tr>
        <tr class="total-row">
            <td style="padding-top: 10px;">Totaalbedrag:</td>
            <td class="text-right" style="padding-top: 10px;">€{{ number_format($totalAmount, 2, ',', '.') }}</td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>{{ $settings['footer_text'] }}</p>
        <p>Vragen over deze factuur? Neem contact op via {{ $settings['email'] }}</p>
    </div>

</body>
</html>
