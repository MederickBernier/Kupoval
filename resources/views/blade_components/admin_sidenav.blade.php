<aside class="w-64 bg-white shadow-lg h-full fixed inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-50"
       x-bind:class="open ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

    <!-- Sidebar Header -->
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

            <!-- Return to Site -->
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

            <!-- Orders Management -->
            <li x-data="{ openOrders: {{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }} }">
                <button @click="openOrders = !openOrders"
                        class="w-full flex items-center px-4 py-2 hover:bg-gray-200">
                    <i class="bi bi-receipt mr-2"></i> {{ __('admin/sidenav.orders') }}
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openOrders}"></i>
                </button>
                <ul x-show="openOrders" class="pl-8 mt-1 space-y-1">
                    <li><a href="{{ route('admin.orders.index') }}"
                           class="block px-4 py-2 hover:bg-gray-100
                                  {{ request()->routeIs('admin.orders.index') ? 'bg-gray-300 font-semibold' : 'text-gray-600' }}">
                            {{ __('admin/sidenav.list_orders') }}
                    </a></li>
                    <li><a href="{{ route('admin.orders.trashed') }}"
                           class="block px-4 py-2 hover:bg-gray-100
                                  {{ request()->routeIs('admin.orders.trashed') ? 'bg-gray-300 font-semibold' : 'text-gray-600' }}">
                            {{ __('admin/sidenav.deactivated_orders') }}
                    </a></li>
                </ul>
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

            <!-- Promotions (Updated Section) -->
            <li x-data="{ openPromotions: {{ request()->routeIs('admin.promotions.*') ? 'true' : 'false' }} }">
                <button @click="openPromotions = !openPromotions"
                        class="w-full flex items-center px-4 py-2 hover:bg-gray-200">
                    <i class="bi bi-tags mr-2"></i> {{ __('admin/sidenav.promotions') }}
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openPromotions}"></i>
                </button>
                <ul x-show="openPromotions" class="pl-8 mt-1 space-y-1">
                    <li>
                        <a href="{{ route('admin.promotions.index') }}"
                           class="block px-4 py-2 hover:bg-gray-100
                                  {{ request()->routeIs('admin.promotions.index') ? 'bg-gray-300 font-semibold' : 'text-gray-600' }}">
                            {{ __('admin/sidenav.list_promotions') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.promotions.trashed') }}"
                           class="block px-4 py-2 hover:bg-gray-100
                                  {{ request()->routeIs('admin.promotions.trashed') ? 'bg-gray-300 font-semibold' : 'text-gray-600' }}">
                            {{ __('admin/sidenav.deactivated_promotions') }}
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Users Management -->
            <li x-data="{ openUsers: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }">
                <button @click="openUsers = !openUsers"
                        class="w-full flex items-center px-4 py-2 hover:bg-gray-200">
                    <i class="bi bi-people mr-2"></i> {{ __('admin/sidenav.users') }}
                    <i class="bi bi-chevron-down ml-auto" x-bind:class="{'rotate-180': openUsers}"></i>
                </button>
                <ul x-show="openUsers" class="pl-8 mt-1 space-y-1">
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                        class="block px-4 py-2 hover:bg-gray-100
                                {{ request()->routeIs('admin.users.index') ? 'bg-gray-300 font-semibold' : 'text-gray-600' }}">
                            {{ __('admin/sidenav.list_users') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.trashed') }}"
                        class="block px-4 py-2 hover:bg-gray-100
                                {{ request()->routeIs('admin.users.trashed') ? 'bg-gray-300 font-semibold' : 'text-gray-600' }}">
                            {{ __('admin/sidenav.deactivated_users') }}
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Accounting -->
            <li>
                <a href="#"
                   class="flex items-center px-4 py-2 hover:bg-gray-200">
                    <i class="bi bi-calculator mr-2"></i> {{ __('admin/sidenav.accounting') }}
                </a>
            </li>

            <!-- Settings -->
            <li>
                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center px-4 py-2 hover:bg-gray-200">
                    <i class="bi bi-gear mr-2"></i> {{ __('admin/sidenav.settings') }}
                </a>
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
