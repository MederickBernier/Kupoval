@extends('emails.layout')

@section('content')
    <h1>Vérifiez votre adresse e-mail</h1>

    <p>Bonjour {{ $name }},</p>

    <p>
        Merci de vous être inscrit ! Pour finaliser votre inscription, veuillez vérifier votre adresse e-mail en cliquant sur le bouton ci-dessous :
    </p>

    <p style="text-align: center;">
        <a href="{{ $verificationUrl }}"
           style="background-color: #28a745; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
            Vérifier mon compte
        </a>
    </p>

    <p>
        Si vous n'avez pas créé ce compte, ignorez simplement cet e-mail.
    </p>

    <p>Meilleures salutations,</p>
    <p><strong>L'équipe {{ config('app.name') }}</strong></p>
@endsection
