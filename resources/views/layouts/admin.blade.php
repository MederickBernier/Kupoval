<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://unpkg.com/htmx.org@2.0.4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 font-sans antialiased" x-data="{ open: false }">

    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('blade_components.admin_sidenav')

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow p-4 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <button @click="open = !open" class="md:hidden p-2 rounded bg-gray-200 hover:bg-gray-300">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-semibold">@yield('page-title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                        <i class="bi bi-person text-gray-500"></i>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
