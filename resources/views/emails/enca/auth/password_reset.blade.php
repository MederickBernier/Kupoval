@extends('emails.layout')

@section('content')
<h1>Hello, {{ $name }}</h1>

<p>You recently requested to reset your password. Click the button below to proceed.</p>

<p style="text-align: center;">
    <a href="{{ $resetUrl }}"
       style="background-color: #dc3545; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
        Reset Password
    </a>
</p>

<p>If you did not request this, please ignore this email. Your password will not be changed.</p>
@endsection
