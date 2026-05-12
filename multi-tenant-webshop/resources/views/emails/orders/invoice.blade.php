<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Factuur {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 40px;
        }
        .header {
            margin-bottom: 40px;
        }
        .header table {
            width: 100%;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .invoice-title {
            font-size: 32px;
            font-weight: 900;
            text-align: right;
            margin: 0;
            color: #000;
        }
        .details-table {
            width: 100%;
            margin-bottom: 40px;
        }
        .details-table td {
            vertical-align: top;
            width: 50%;
        }
        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        .company-info p, .customer-info p {
            margin: 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .items-table th {
            text-align: left;
            border-bottom: 2px solid #000;
            padding: 10px 0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .items-table td {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .text-right {
            text-align: right;
        }
        .totals-table {
            width: 40%;
            margin-left: 60%;
        }
        .totals-table td {
            padding: 5px 0;
        }
        .total-row {
            font-weight: bold;
            font-size: 18px;
            border-top: 2px solid #000;
        }
        .footer {
            margin-top: 100px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            background: #f4f4f5;
            border-radius: 100px;
            font-size: 10px;
            font-weight: bold;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <table>
                <tr>
                    <td>
                        @if($settings['logo'])
                            <img src="{{ public_path('storage/' . $settings['logo']) }}" style="max-height: 60px;">
                        @else
                            <div class="logo">{{ $settings['company_name'] }}</div>
                        @endif
                    </td>
                    <td>
                        <h1 class="invoice-title">FACTUUR</h1>
                        <div class="text-right">
                            <span class="badge">#{{ $order->order_number }}</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="details-table">
            <tr>
                <td>
                    <div class="section-title">Van</div>
                    <div class="company-info">
                        <p><strong>{{ $settings['company_name'] }}</strong></p>
                        <p>{!! nl2br(e($settings['address'])) !!}</p>
                        @if($settings['vat_number'])
                            <p>BTW: {{ $settings['vat_number'] }}</p>
                        @endif
                        <p>{{ $settings['email'] }}</p>
                    </div>
                </td>
                <td class="text-right">
                    <div class="section-title">Factuur naar</div>
                    <div class="customer-info">
                        <p><strong>{{ $order->customer_details['name'] ?? $order->customer?->name }}</strong></p>
                        <p>{{ $order->customer_details['email'] ?? $order->customer?->email }}</p>
                        <p>Datum: {{ $order->created_at->format('d-m-Y') }}</p>
                    </div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Omschrijving</th>
                    <th class="text-right">Aantal</th>
                    <th class="text-right">Prijs</th>
                    <th class="text-right">Totaal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product?->name ?? 'Product' }}</strong>
                            @if($item->variation_details)
                                <br><small style="color: #666">{{ collect($item->variation_details)->map(fn($v, $k) => "$k: $v")->implode(', ') }}</small>
                            @endif
                        </td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">€{{ number_format($item->price / 100, 2, ',', '.') }}</td>
                        <td class="text-right">€{{ number_format(($item->price * $item->quantity) / 100, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td>Subtotaal</td>
                <td class="text-right">€{{ number_format(($order->total_amount - $order->tax_amount) / 100, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>BTW</td>
                <td class="text-right">€{{ number_format($order->tax_amount / 100, 2, ',', '.') }}</td>
            </tr>
            @if($order->shipping_amount > 0)
                <tr>
                    <td>Verzendkosten</td>
                    <td class="text-right">€{{ number_format($order->shipping_amount / 100, 2, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td style="padding-top: 10px;">TOTAAL</td>
                <td class="text-right" style="padding-top: 10px;">€{{ number_format($order->total_amount / 100, 2, ',', '.') }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>{{ $settings['footer_text'] }}</p>
            <p>&copy; {{ date('Y') }} {{ $settings['company_name'] }}. Alle rechten voorbehouden.</p>
        </div>
    </div>
</body>
</html>
