<x-mail::message>
# Bedankt voor je bestelling!

Je betaling voor bestelling **{{ $order->order_number }}** is succesvol ontvangen. We gaan direct aan de slag om je pakketje klaar te maken.

<x-mail::table>
| Product | Aantal | Prijs |
| :--- | :---: | :--- |
@foreach($items as $item)
| {{ $item->product_name }} | {{ $item->quantity }}x | €{{ number_format($item->price * $item->quantity / 100, 2) }} |
@endforeach
| **Totaal** | | **€{{ number_format($order->total_amount / 100, 2) }}** |
</x-mail::table>

Je ontvangt een nieuwe mail zodra je bestelling is verzonden.

Bedankt voor het vertrouwen,<br>
{{ config('app.name') }} Team
</x-mail::message>
