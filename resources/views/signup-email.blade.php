
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Envoi de mail pour l'inscription sur le site de Youpigoo : Youpigoo</title>
</head>

<body style="margin: 100px">

    <h3 style="font-weight:bold; text-align:center">{{ config('app.name') }}</h3>

    Bienvenue {{ $nom_user }} {{ $prenoms_user }}
    
    <p> S'il vous plait , veuillez cliquer sur le lien pour vérifier votre
        email et activer votre compte sur le site de Youpigoo
    </p>
    
    <p>
        <a href="http://127.0.0.1.8000/api/auth/    confirmation-email/verificationcode/{{ $verification_code }}">
            Cliquez ici!
        </a>
    </p>

    Merci,<br>
    {{ config('app.name') }}

</body>

</html>
