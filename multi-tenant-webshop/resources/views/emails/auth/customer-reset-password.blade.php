<x-mail::message>
# Wachtwoord Reset Aanvraag

Beste {{ $customerName }},

We hebben een verzoek ontvangen om het wachtwoord van je account te resetten. Klik op de onderstaande knop om een nieuw wachtwoord in te stellen:

<x-mail::button :url="$resetUrl">
Wachtwoord Resetten
</x-mail::button>

Deze link is 60 minuten geldig. Heb je dit verzoek niet zelf ingediend? Dan hoef je niets te doen; je account blijft veilig.

Met vriendelijke groet,<br>
{{ config('app.name') }} Team
</x-mail::message>
