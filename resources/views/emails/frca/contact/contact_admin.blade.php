<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message de Contact</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">

    <h2>Nouveau Message de Contact</h2>

    <p><strong>Nom :</strong> {{ $name }}</p>
    <p><strong>Email :</strong> {{ $email }}</p>
    <p><strong>Message :</strong></p>
    <blockquote style="border-left: 4px solid #007bff; padding-left: 10px; color: #555;">
        {!! nl2br(e($messageContent)) !!}
    </blockquote>

    <p>Répondez à cet email pour répondre à ce message.</p>

</body>
</html>
