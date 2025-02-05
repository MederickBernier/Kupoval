<aside class="w-64 bg-white shadow-lg h-full fixed inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-50"
       x-bind:class="open ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

    <!-- Header du Menu -->
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-lg font-semibold"><a href="{{ route('home') }}">Kupoval</a></h1>
        <button @click="open = false" class="md:hidden text-gray-600">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="mt-4">
        <ul class="space-y-1">
            <!-- Return to Website -->
            <li>
                <a href="{{ route('home') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-house-fill mr-2"></i> Return to Website
                </a>
            </li>
            <!-- Return to Website (target=_blank) -->
            <li>
                <a href="{{ route('home') }}" target="_blank" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-house-fill mr-2"></i> Return to Website (in new tab)
                </a>
            </li>
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-house-door mr-2"></i> Dashboard
                </a>
            </li>


            <!-- Events -->
            <li x-data="{ openSub: false }">
                <button @click="openSub = !openSub" class="w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-calendar-event mr-2"></i> Events
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openSub}"></i>
                </button>
                <ul x-show="openSub" class="pl-8 mt-1 space-y-1">
                    <li><a href="{{ route('admin.events.list') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">List Events</a></li>
                    <li><a href="{{ route('admin.events.trashed') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Deactivated Events</a></li>
                </ul>
            </li>

            <!-- Artworks -->
            <li x-data="{ openSub: false }">
                <button @click="openSub = !openSub" class="w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-brush mr-2"></i> Artworks
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openSub}"></i>
                </button>
                <ul x-show="openSub" class="pl-8 mt-1 space-y-1">
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">List Artworks</a></li>
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Add Artwork</a></li>
                </ul>
            </li>

            <!-- Orders -->
            <li>
                <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-card-list mr-2"></i> Orders
                </a>
            </li>

            <!-- Users -->
            <li x-data="{ openUsers: false }">
                <button @click="openUsers = !openUsers" class="w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-people mr-2"></i> Users
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openUsers}"></i>
                </button>
                <ul x-show="openUsers" class="pl-8 mt-1 space-y-1">
                    <li>
                        <a href="{{ route('admin.users.list') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">
                            <i class="bi bi-list-ul mr-2"></i> Active Users
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.trashed') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">
                            <i class="bi bi-archive mr-2"></i> Deactivated Users
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Settings -->
            <li x-data="{ openSub: false }">
                <button @click="openSub = !openSub" class="w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-gear mr-2"></i> Settings
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openSub}"></i>
                </button>
                <ul x-show="openSub" class="pl-8 mt-1 space-y-1">
                    <li><a href="{{ route('admin.settings.list') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">General Settings</a></li>
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Social Media Links</a></li>
                </ul>
            </li>

            <!-- Statics -->
            <li x-data="{ openSub: false }">
                <button @click="openSub = !openSub" class="w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-bar-chart mr-2"></i> Statics
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openSub}"></i>
                </button>
                <ul x-show="openSub" class="pl-8 mt-1 space-y-1">
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">About Page</a></li>
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Contact Page</a></li>
                </ul>
            </li>

            <!-- Accounting -->
            <li x-data="{ openSub: false }">
                <button @click="openSub = !openSub" class="w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-cash mr-2"></i> Accounting
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openSub}"></i>
                </button>
                <ul x-show="openSub" class="pl-8 mt-1 space-y-1">
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Financial Summary</a></li>
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Revenue Reports</a></li>
                </ul>
            </li>

            <!-- Logout -->
            <li class="mt-6">
                <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 text-red-600 hover:bg-red-100">
                    <i class="bi bi-box-arrow-left mr-2"></i> Logout
                </a>
            </li>

            <!-- Profile -->
            <li class="mt-6 flex items-center px-4 py-2 text-gray-700 border-t pt-4">
                <i class="bi bi-person-circle text-xl mr-2"></i> Profile
            </li>
        </ul>
    </nav>
</aside>
