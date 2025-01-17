<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kupoval')</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-page text-body font-body min-h-screen flex flex-col">
    <header>
        @include('blade_components.public_navbar')
    </header>
    <main class="container mx-auto px-4 md:px-8 py8 flex-grow">
        @yield('content')
    </main>
    <footer class="bg-neutral text-center py-6">
        <p class="text-sm text-heading">
            {{ __('public/interface.footer_copyright') }}
        </p>
        <div class="flex justify-center space-x-6 mt-4">
            <a href="#" class="text-link hover:underline">{{ __('public/interface.social_linkedin') }}</a>
            <a href="#" class="text-link hover:underline">{{ __('public/interface.social_facebook') }}</a>
            <a href="#" class="text-link hover:underline">{{ __('public/interface.social_instagram') }}</a>
        </div>
    </footer>
</body>
</html>
