<x-mail::message>
# Goed nieuws! Je bestelling is onderweg

Je bestelling **{{ $order->order_number }}** is zojuist ingepakt en overgedragen aan de bezorgdienst.

@if($trackingCode)
**Track & Trace code:** `{{ $trackingCode }}`
@endif

<x-mail::table>
| Product | Aantal |
| :--- | :---: |
@foreach($items as $item)
| {{ $item->product_name }} | {{ $item->quantity }}x |
@endforeach
</x-mail::table>

Mocht je vragen hebben over de levering, aarzel dan niet om contact met ons op te nemen.

Met vriendelijke groet,<br>
{{ config('app.name') }} Team
</x-mail::message>
