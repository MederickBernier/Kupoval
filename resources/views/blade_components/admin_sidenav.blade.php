<aside class="w-64 bg-white shadow-lg fixed inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-50"
       :class="{ '-translate-x-full': !open, 'translate-x-0': open }">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-lg font-semibold"><a href="{{ route('home') }}">Kupoval</a></h1>
        <button @click="open = false" class="md:hidden p-2 rounded bg-gray-200 hover:bg-gray-300">
            <i class="bi bi-x-lg text-xl"></i>
        </button>
    </div>
    <nav class="mt-4">
        <ul>
            <li class="mb-2">
                <a href="{{ route('home') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-arrow-left mr-2"></i> Back to Website
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-house-door mr-2"></i> Dashboard
                </a>
            </li>

            <!-- Section Events -->
            <li class="mb-2" x-data="{ openSub: false }">
                <button @click="openSub = !openSub" class="w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-calendar-event mr-2"></i> Events
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openSub}"></i>
                </button>
                <ul x-show="openSub" class="pl-8 mt-1">
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">All Events</a></li>
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Add Event</a></li>
                </ul>
            </li>

            <!-- Section Artworks -->
            <li class="mb-2" x-data="{ openSub: false }">
                <button @click="openSub = !openSub" class="w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-brush mr-2"></i> Artworks
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openSub}"></i>
                </button>
                <ul x-show="openSub" class="pl-8 mt-1">
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">All Artworks</a></li>
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Add Artwork</a></li>
                </ul>
            </li>

            <!-- Section Orders -->
            <li class="mb-2" x-data="{ openSub: false }">
                <button @click="openSub = !openSub" class="w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-card-list mr-2"></i> Orders
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openSub}"></i>
                </button>
                <ul x-show="openSub" class="pl-8 mt-1">
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">All Orders</a></li>
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Pending Orders</a></li>
                </ul>
            </li>

            <!-- Section Users -->
            <li class="mb-2" x-data="{ openSub: false }">
                <button @click="openSub = !openSub" class="w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-people mr-2"></i> Users
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openSub}"></i>
                </button>
                <ul x-show="openSub" class="pl-8 mt-1">
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">All Users</a></li>
                    <li><a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Manage Roles</a></li>
                </ul>
            </li>

            <!-- Logout -->
            <li class="mt-6">
                <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 text-red-600 hover:bg-red-100">
                    <i class="bi bi-box-arrow-left mr-2"></i> Logout
                </a>
            </li>
        </ul>
    </nav>
</aside>
