@extends('emails.layout')

@section('content')
<h1>{{ __('Hello, :name', ['name' => $name]) }}</h1>

<p>{{ __('Thank you for registering! Please verify your email address to activate your account.') }}</p>

<p style="text-align: center;">
    <a href="{{ $verificationUrl }}"
       style="background-color: #007bff; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
        {{ __('Verify Email') }}
    </a>
</p>

<p>{{ __('If you did not create an account, no further action is required.') }}</p>
@endsection
