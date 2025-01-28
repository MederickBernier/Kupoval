<aside class="w-64 bg-white shadow-lg">
    <div class="p-6 border-b">
        <h1 class="text-lg font-semibold">Kupoval</h1>
    </div>
    <nav class="mt-4">
        <ul>
            <li class="mb-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-house-door mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="mb-2">
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-calendar-event mr-2"></i>
                    Events
                </a>
            </li>
            <li class="mb-2">
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-brush mr-2"></i>
                    Artworks
                </a>
            </li>
            <li class="mb-2">
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-card-list mr-2"></i>
                    Orders
                </a>
            </li>
            <li class="mb-2">
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-people mr-2"></i>
                    Users
                </a>
            </li>
            <li class="mb-2">
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-gear mr-2"></i>
                    Settings
                </a>
            </li>
            <li class="mt-6">
                <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 text-red-600 hover:bg-red-100">
                    <i class="bi bi-box-arrow-left mr-2"></i>
                    Logout
                </a>
            </li>
        </ul>
    </nav>
</aside>
