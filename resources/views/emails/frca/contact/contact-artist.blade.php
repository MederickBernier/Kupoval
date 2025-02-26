<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Message de Contact</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9; }
        h2 { color: #333; }
        p { margin: 10px 0; }
        .footer { margin-top: 20px; font-size: 0.9em; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Nouveau Message de {{ $name }}</h2>
        <p><strong>Email :</strong> {{ $email }}</p>
        <p><strong>Sujet :</strong> {{ $subject }}</p>
        <p><strong>Message :</strong></p>
        <p>{!! nl2br(e($messageContent)) !!}</p>

        <p class="footer">Ce message a été envoyé via le formulaire de contact de votre profil d'artiste.</p>
    </div>
</body>
</html>
