@extends('layouts.public')

@section('content')
<section class="my-12">
    <div class="container mx-auto max-w-md text-center">
        <h1 class="text-3xl font-title text-heading mb-6">{{ __('Verify Your Email Address') }}</h1>

        <p class="text-body mb-4">
            {{ __('A verification link has been sent to your email address:') }}
            <strong class="text-heading">{{ Auth::user()->email }}</strong>.
        </p>

        <p class="text-body mb-8">
            {{ __('Please check your inbox and follow the instructions. If you did not receive the email, you can request a new one below.') }}
        </p>

        @if (session('status'))
            <div class="text-sm text-green-600 mb-4">
                {{ session('status') }}
            </div>
        @endif

        <!-- Resend Verification Email Form -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-navbar-hover transition">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <!-- Optional: Logout if the wrong email was used -->
        <div class="mt-6">
            <p class="text-sm text-body">
                {{ __('Used the wrong email?') }}
                <a href="{{ route('logout') }}" class="text-accent hover:underline"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Log out and try again') }}
                </a>
            </p>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</section>
@endsection
