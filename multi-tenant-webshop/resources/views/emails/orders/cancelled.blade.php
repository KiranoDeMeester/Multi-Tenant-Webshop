<x-mail::message>
# Bestelling Geannuleerd

Je bestelling met bestelnummer **{{ $order->order_number }}** is geannuleerd.

Eventueel gereserveerde voorraad is vrijgegeven. Mocht er reeds een betaling zijn voldaan, dan wordt het bedrag van **€{{ number_format($order->total_amount / 100, 2, ',', '.') }}** binnen enkele werkdagen teruggestort via de oorspronkelijke betaalmethode.

Mocht je vragen hebben of denken dat dit een vergissing is, neem dan gerust contact met ons op.

Met vriendelijke groet,<br>
{{ config('app.name') }} Team
</x-mail::message>
