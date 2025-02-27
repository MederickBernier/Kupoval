<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Bona+Nova+SC&family=Old+Standard+TT&family=Signika:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css">
    <!-- Optional Theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.theme.min.css">

    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/glide.min.js"></script>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

    <!-- SimpleLightbox CDN -->
    <link href="https://cdn.jsdelivr.net/npm/simplelightbox@2.4.0/dist/simple-lightbox.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/simplelightbox@2.4.0/dist/simple-lightbox.min.js"></script>


    <title>@yield('title', 'Kupoval')</title>
    @livewireStyles
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-page text-body font-body min-h-screen flex flex-col">

    <x-toast-notification /> <!-- Include Toast Component -->

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
        @include('blade_components.public_footer')
    </footer>

    <script>
        window.addEventListener('reload-page', function () {
            console.log('🔄 Rechargement forcé après changement de langue');
            location.reload();
        });

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                window.Alpine.store('toastHandler').addToast("{{ session('success') }}", "success");
            @endif

            @if(session('error'))
                window.Alpine.store('toastHandler').addToast("{{ session('error') }}", "error");
            @endif

            @if(session('warning'))
                window.Alpine.store('toastHandler').addToast("{{ session('warning') }}", "warning");
            @endif
        });
    </script>
</body>
</html>
