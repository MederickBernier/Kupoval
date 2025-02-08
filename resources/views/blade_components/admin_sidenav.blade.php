<aside class="w-64 bg-white shadow-lg h-full fixed inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-50"
       x-bind:class="open ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

    <!-- Header du Menu -->
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-lg font-semibold">
            <a href="{{ route('home') }}">{{ __('admin/sidenav.brand') }}</a>
        </h1>
        <button @click="open = false" class="md:hidden text-gray-600">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="mt-4">
        <ul class="space-y-1">

            <!-- Retour au site -->
            <li>
                <a href="{{ route('home') }}"
                   class="flex items-center px-4 py-2 hover:bg-gray-200
                          {{ request()->routeIs('home') ? 'bg-gray-300 font-semibold' : 'text-gray-700' }}">
                    <i class="bi bi-house-fill mr-2"></i> {{ __('admin/sidenav.return_site') }}
                </a>
            </li>
            <li>
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center px-4 py-2 hover:bg-gray-200
                          {{ request()->routeIs('home') ? 'bg-gray-300 font-semibold' : 'text-gray-700' }}">
                    <i class="bi bi-box-arrow-up-right mr-2"></i> {{ __('admin/sidenav.return_site_new_tab') }}
                </a>
            </li>

            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-2 hover:bg-gray-200
                          {{ request()->routeIs('admin.dashboard') ? 'bg-gray-300 font-semibold' : 'text-gray-700' }}">
                    <i class="bi bi-speedometer2 mr-2"></i> {{ __('admin/sidenav.dashboard') }}
                </a>
            </li>

            <!-- Artworks -->
            <li x-data="{ openArtworks: {{ request()->routeIs('admin.artworks.*') ? 'true' : 'false' }} }">
                <button @click="openArtworks = !openArtworks"
                        class="w-full flex items-center px-4 py-2 hover:bg-gray-200">
                    <i class="bi bi-brush mr-2"></i> {{ __('admin/sidenav.artworks') }}
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openArtworks}"></i>
                </button>
                <ul x-show="openArtworks" class="pl-8 mt-1 space-y-1">
                    <li><a href="{{ route('admin.artworks.index') }}"
                           class="block px-4 py-2 hover:bg-gray-100
                                  {{ request()->routeIs('admin.artworks.index') ? 'bg-gray-300 font-semibold' : 'text-gray-600' }}">
                            {{ __('admin/sidenav.list_artworks') }}
                    </a></li>
                    <li><a href="{{ route('admin.artworks.trashed') }}"
                           class="block px-4 py-2 hover:bg-gray-100
                                  {{ request()->routeIs('admin.artworks.trashed') ? 'bg-gray-300 font-semibold' : 'text-gray-600' }}">
                            {{ __('admin/sidenav.deactivated_artworks') }}
                    </a></li>
                </ul>
            </li>

            <!-- Categories -->
            <li x-data="{ openCategories: {{ request()->routeIs('admin.categories.*') ? 'true' : 'false' }} }">
                <button @click="openCategories = !openCategories"
                        class="w-full flex items-center px-4 py-2 hover:bg-gray-200">
                    <i class="bi bi-tags mr-2"></i> {{ __('admin/sidenav.categories') }}
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openCategories}"></i>
                </button>
                <ul x-show="openCategories" class="pl-8 mt-1 space-y-1">
                    <li><a href="{{ route('admin.categories.index') }}"
                           class="block px-4 py-2 hover:bg-gray-100
                                  {{ request()->routeIs('admin.categories.index') ? 'bg-gray-300 font-semibold' : 'text-gray-600' }}">
                            {{ __('admin/sidenav.list_categories') }}
                    </a></li>
                    <li><a href="{{ route('admin.categories.trashed') }}"
                           class="block px-4 py-2 hover:bg-gray-100
                                  {{ request()->routeIs('admin.categories.trashed') ? 'bg-gray-300 font-semibold' : 'text-gray-600' }}">
                            {{ __('admin/sidenav.deactivated_categories') }}
                    </a></li>
                </ul>
            </li>

            <!-- Events -->
            <li x-data="{ openEvents: {{ request()->routeIs('admin.events.*') ? 'true' : 'false' }} }">
                <button @click="openEvents = !openEvents"
                        class="w-full flex items-center px-4 py-2 hover:bg-gray-200">
                    <i class="bi bi-calendar-event mr-2"></i> {{ __('admin/sidenav.events') }}
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openEvents}"></i>
                </button>
                <ul x-show="openEvents" class="pl-8 mt-1 space-y-1">
                    <li><a href="{{ route('admin.events.list') }}"
                           class="block px-4 py-2 hover:bg-gray-100
                                  {{ request()->routeIs('admin.events.list') ? 'bg-gray-300 font-semibold' : 'text-gray-600' }}">
                            {{ __('admin/sidenav.list_events') }}
                        </a></li>
                    <li><a href="{{ route('admin.events.trashed') }}"
                           class="block px-4 py-2 hover:bg-gray-100
                                  {{ request()->routeIs('admin.events.trashed') ? 'bg-gray-300 font-semibold' : 'text-gray-600' }}">
                            {{ __('admin/sidenav.deactivated_events') }}
                        </a></li>
                </ul>
            </li>

            <!-- Artists -->
            <li x-data="{ openArtists: {{ request()->routeIs('admin.artists.*') ? 'true' : 'false' }} }">
                <button @click="openArtists = !openArtists"
                        class="w-full flex items-center px-4 py-2 hover:bg-gray-200">
                    <i class="bi bi-person-bounding-box mr-2"></i> {{ __('admin/sidenav.artists') }}
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openArtists}"></i>
                </button>
                <ul x-show="openArtists" class="pl-8 mt-1 space-y-1">
                    <li><a href="{{ route('admin.artists.index') }}"
                           class="block px-4 py-2 hover:bg-gray-100
                                  {{ request()->routeIs('admin.artists.index') ? 'bg-gray-300 font-semibold' : 'text-gray-600' }}">
                            {{ __('admin/sidenav.list_artists') }}
                    </a></li>
                    <li><a href="{{ route('admin.artists.trashed') }}"
                           class="block px-4 py-2 hover:bg-gray-100
                                  {{ request()->routeIs('admin.artists.trashed') ? 'bg-gray-300 font-semibold' : 'text-gray-600' }}">
                            {{ __('admin/sidenav.deactivated_artists') }}
                    </a></li>
                </ul>
            </li>

            <!-- Logout -->
            <li class="mt-6">
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit"
                            class="flex items-center w-full px-4 py-2 text-red-600 hover:bg-red-100">
                        <i class="bi bi-box-arrow-left mr-2"></i> {{ __('admin/sidenav.logout') }}
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>
