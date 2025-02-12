<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

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
            <a href="#" class="text-link hover:underline"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-link hover:underline"><i class="bi bi-instagram"></i></a>
        </div>
    </footer>
    @stack('scripts')
    <script>
        window.addEventListener('reload-page', function () {
            console.log('🔄 Rechargement forcé après changement de langue');
            location.reload();
        });
        document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.editor').forEach(editor => {
            ClassicEditor
                .create(editor, {
                    toolbar: [
                        'bold', 'italic', 'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'blockQuote', 'undo', 'redo'
                    ],
                    language: 'fr',
                    height: 400
                })
                .then(editorInstance => {
                    editorInstance.ui.view.editable.element.style.minHeight = "250px";
                })
                .catch(error => console.error("CKEditor Error:", error));
        });
    });
    </script>
    @livewireScripts
</body>
</html>
