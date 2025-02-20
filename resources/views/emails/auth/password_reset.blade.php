@extends('emails.layout')

@section('content')
<h1>{{ __('Hello, :name', ['name' => $name]) }}</h1>

<p>{{ __('You recently requested to reset your password. Click the button below to proceed.') }}</p>

<p style="text-align: center;">
    <a href="{{ $resetUrl }}"
       style="background-color: #dc3545; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
        {{ __('Reset Password') }}
    </a>
</p>

<p>{{ __('If you did not request this, please ignore this email. Your password will not be changed.') }}</p>
@endsection
