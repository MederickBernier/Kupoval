@extends('layouts.public')

@section('content')
<section class="my-12">
    <div class="container mx-auto max-w-md text-center">
        <h1 class="text-3xl font-title text-heading mb-6">{{ __('Verify Your Email Address') }}</h1>
        <p class="text-body mb-8">
            {{ __('Please check your email for a verification link. If you did not receive the email, you can request a new one below.') }}
        </p>

        @if (session('status'))
            <div class="text-sm text-green-600 mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-navbar-hover transition">
                {{ __('Resend Verification Email') }}
            </button>
        </form>
    </div>
</section>
@endsection
