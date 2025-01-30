<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de l'Email</title>
</head>
<body>
    <h1>Salut {{ $user->username }},</h1>
    <p>Clique sur le bouton ci-dessous pour vérifier ton adresse email :</p>
    <p><a href="{{ $url }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Vérifier mon email</a></p>
    <p>Ce lien expirera dans 60 minutes.</p>
</body>
</html>
