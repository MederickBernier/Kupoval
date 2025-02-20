@extends('emails.layout')

@section('content')
    <h1>{{ __('emails.auth.verify_title') }}</h1>

    <p>{{ __('emails.auth.verify_message', ['name' => $name]) }}</p>

    <p style="text-align: center;">
        @if(isset($verificationUrl) && !empty($verificationUrl))
            <a href="{!! $verificationUrl !!}"
               style="background-color: #28a745; color: #ffffff; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                {{ __('emails.auth.verify_button') }}
            </a>
        @else
            <strong style="color: red;">Verification URL is missing!</strong>
        @endif
    </p>

    <p>{{ __('emails.auth.verify_footer') }}</p>
@endsection
