@extends('emails.layout')

@section('content')
<h1>Bonjour, {{ $name }}</h1>

<p>Vous avez récemment demandé la réinitialisation de votre mot de passe. Cliquez sur le bouton ci-dessous pour continuer.</p>

<p style="text-align: center;">
    <a href="{{ $resetUrl }}"
       style="background-color: #dc3545; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
        Réinitialiser le mot de passe
    </a>
</p>

<p>Si vous n'êtes pas à l'origine de cette demande, ignorez cet e-mail. Votre mot de passe ne sera pas modifié.</p>
@endsection
