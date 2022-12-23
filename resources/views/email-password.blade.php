@component('mail::message')
Bienvenue {{ $user_name }}
@component('mail::button', ['url' => route('reinitialiser.reset-code', $reset_code)])
Cliquer ici pour modifier votre mot de passe
@endcomponent
<p>Ou copiez et collez le lien suivant dans votre navigateur</p>
<p>
    <a href="{{ route('reinitialiser.reset-code', $reset_code) }}">
        Reinitialiser mon mot de passe
    </a>
</p>
Merci,<br>
{{ config('app.name') }}
@endcomponent