@extends('emails.layout')

@section('content')
    <h1>Verify Your Email Address</h1>

    <p>Hello {{ $name }},</p>

    <p>
        Thank you for signing up! To complete your registration, please verify your email address by clicking the button below:
    </p>

    <p style="text-align: center;">
        <a href="{{ $verificationUrl }}"
           style="background-color: #28a745; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
            Verify My Account
        </a>
    </p>

    <p>
        If you did not create this account, you can safely ignore this email.
    </p>

    <p>Best regards,</p>
    <p><strong>{{ config('app.name') }} Team</strong></p>
@endsection
