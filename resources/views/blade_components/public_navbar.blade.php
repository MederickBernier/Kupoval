<nav class="bg-navbar text-white shadow-md" x-data="{ open: false }">
    <div class="container mx-auto flex justify-between items-center py-4 px-4 md:px-6">
        <!-- Logo -->
        <div>
            <a href="{{ route('home') }}" class="text-2xl font-title tracking-wide text-heading">
                Kupoval
            </a>
        </div>

        <!-- Hamburger Menu for Mobile -->
        <div class="md:hidden">
            <button
                @click="open = !open"
                :aria-expanded="open"
                aria-label="Toggle navigation menu"
                class="focus:outline-none focus:ring-2 focus:ring-accent rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links (Desktop) -->
        <div class="hidden md:flex space-x-6">
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.home') }}</a>
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.gallery') }}</a>
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.events') }}</a>
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.bio') }}</a>
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.shop') }}</a>
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.about') }}</a>
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.contact') }}</a>
        </div>

        <!-- Authentication Links (Desktop) -->
        <div class="hidden md:flex space-x-4">
            @guest
                <a href="#" class="px-4 py-2 bg-cta text-white rounded-full hover:bg-navbar-hover transition">
                    {{ __('public/interface.login') }}
                </a>
                <a href="#" class="px-4 py-2 bg-accent text-white rounded-full hover:bg-navbar-hover transition">
                    {{ __('public/interface.register') }}
                </a>
            @else
                <a href="#" class="px-4 py-2 bg-accent text-white rounded-full hover:bg-navbar-hover transition">
                    {{ __('public/interface.dashboard') }}
                </a>
                <form action="#" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-cta text-white rounded-full hover:bg-navbar-hover transition">
                        {{ __('public/interface.logout') }}
                    </button>
                </form>
            @endguest
        </div>
    </div>

    <!-- Mobile Menu -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-y-0"
        x-transition:enter-end="opacity-100 transform scale-y-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform scale-y-100"
        x-transition:leave-end="opacity-0 transform scale-y-0"
        @click.away="open = false"
        class="md:hidden bg-navbar text-white absolute inset-x-0 top-0 mt-16 shadow-md z-50">
        <div class="flex flex-col space-y-4 py-4 px-6">
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.home') }}</a>
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.gallery') }}</a>
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.events') }}</a>
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.bio') }}</a>
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.shop') }}</a>
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.about') }}</a>
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.contact') }}</a>
            @guest
                <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.login') }}</a>
                <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.register') }}</a>
            @else
                <a href="#" class="hover:text-navbar-hover">Dashboard</a>
                <form action="#" method="POST">
                    @csrf
                    <button type="submit" class="hover:text-navbar-hover">Logout</button>
                </form>
            @endguest
        </div>
    </div>
</nav>
