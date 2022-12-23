<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contactez-nous :: Mycartraders</title>
</head>

<body>
    <div style="font-size: 20px;font-weight: 500;">
        <p>Nom : {{ $dataReceived['nom'] }}</p>
        <p>Email : {{ $dataReceived['email'] }}</p>
        <p>Sujet : {{ $dataReceived['sujet'] }}</p>
        <p>Message : {{ $dataReceived['message'] }}</p>
    </div>
</body>

</html>