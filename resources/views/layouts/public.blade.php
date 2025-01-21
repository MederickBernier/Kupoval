<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <title>@yield('title', 'Kupoval')</title>
    @livewireStyles
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-page text-body font-body min-h-screen flex flex-col">
    <header>
        @include('blade_components.public_navbar')
    </header>
    @yield('hero')
    <main class="px-4 md:px-8 py-8 flex-grow">
        @yield('content')
    </main>
    <footer class="bg-neutral text-center py-6">
        <p class="text-sm text-heading">
            {!! __('public/interface.footer_copyright') !!}
        </p>
        <div class="flex justify-center space-x-6 mt-4">
            <a href="#" class="text-link hover:underline">{{ __('public/interface.social_facebook') }}</a>
            <a href="#" class="text-link hover:underline">{{ __('public/interface.social_instagram') }}</a>
        </div>
    </footer>
    @livewireScripts
</body>
</html>
