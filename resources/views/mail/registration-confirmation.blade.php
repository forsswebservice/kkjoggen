<x-mail::message>
# Bokningsbekräftelse {{ $competitor->competitionYear->name }}

Tack för din anmälan!

Din anmälan har referensnummer **{{ $competitor->id }}**.

Du kan se din bokningsbekräftelse via länken nedan.

<x-mail::button :url="$competitor->getConfirmationURL()">
Visa bokningsbekräftelse
</x-mail::button>

Om du har frågor om din bokning kan du kontakta oss på **kkjoggen@gmail.com**.

Vänligen,<br>
{{ config('app.name') }}
</x-mail::message>
