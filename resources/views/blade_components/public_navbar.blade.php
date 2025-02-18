<nav class="bg-navbar text-white shadow-md" x-data="{ open: false, dropdown: false }">
    <div class="container mx-auto flex justify-between items-center py-4 px-4 md:px-6">
        <!-- Logo -->
        <div>
            <a href="{{ route('home') }}" class="text-4xl font-title tracking-wide text-heading drop-shadow-lg">
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
            <a href="{{ route('home') }}" class="relative hover:text-navbar-hover group text-2xl">
                {{ __('public/interface.home') }}
            </a>
            <a href="{{ route('gallery') }}" class="relative hover:text-navbar-hover group text-2xl">
                {{ __('public/interface.gallery') }}
            </a>
            <a href="{{ route('events') }}" class="relative hover:text-navbar-hover group text-2xl">
                {{ __('public/interface.events') }}
            </a>
            <a href="{{ route('bio.index') }}" class="relative hover:text-navbar-hover group text-2xl">
                {{ __('public/interface.bio') }}
            </a>
            <a href="{{ route('shop.index') }}" class="relative hover:text-navbar-hover group text-2xl">
                {{ __('public/interface.shop') }}
            </a>
            <a href="{{ route('about') }}" class="relative hover:text-navbar-hover group text-2xl">
                {{ __('public/interface.about') }}
            </a>
            <a href="{{ route('contact') }}" class="relative hover:text-navbar-hover group text-2xl">
                {{ __('public/interface.contact') }}
            </a>
        </div>

        <!-- Authentication Links (Desktop) -->
        <div class="hidden md:flex space-x-4 relative">
            @guest
                <a href="{{ route('login') }}" class="px-4 py-2 bg-cta text-white rounded-full hover:bg-navbar-hover transition">
                    {{ __('public/interface.login') }}
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-accent text-white rounded-full hover:bg-navbar-hover transition">
                    {{ __('public/interface.register') }}
                </a>
            @else
                <div class="relative group">
                    <button @click="dropdown = !dropdown" class="px-4 py-2 bg-accent text-white rounded-full hover:bg-navbar-hover transition flex items-center">
                        {{ Auth::user()->profile ? Auth::user()->profile->first_name . ' ' . Auth::user()->profile->last_name : Auth::user()->username }}
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="dropdown" @click.away="dropdown = false" class="absolute bg-white text-gray-800 rounded-lg shadow-lg mt-2 w-48 right-0 z-10">
                        <a href="{{ route('user.profile') }}" class="block px-4 py-2 hover:bg-gray-100">
                            <i class="bi bi-person mr-2"></i> {{ __('public/interface.user_profile') }}
                        </a>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">
                                <i class="bi bi-speedometer2 mr-2"></i> {{ __('public/interface.dashboard') }}
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                <i class="bi bi-box-arrow-right mr-2"></i> {{ __('public/interface.logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" @click.away="open = false" class="md:hidden bg-navbar text-white absolute inset-x-0 top-0 mt-16 shadow-md z-50">
        <div class="flex flex-col space-y-4 py-4 px-6">
            <a href="{{ route('home') }}" class="hover:text-navbar-hover">{{ __('public/interface.home') }}</a>
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.gallery') }}</a>
            <a href="{{ route('events') }}" class="hover:text-navbar-hover">{{ __('public/interface.events') }}</a>
            <a href="{{ route('bio.index') }}" class="hover:text-navbar-hover">{{ __('public/interface.bio') }}</a>
            <a href="#" class="hover:text-navbar-hover">{{ __('public/interface.shop') }}</a>
            <a href="{{ route('about') }}" class="hover:text-navbar-hover">{{ __('public/interface.about') }}</a>
            <a href="{{ route('contact') }}" class="hover:text-navbar-hover">{{ __('public/interface.contact') }}</a>

            @guest
                <a href="{{ route('login') }}" class="hover:text-navbar-hover">{{ __('public/interface.login') }}</a>
                <a href="{{ route('register') }}" class="hover:text-navbar-hover">{{ __('public/interface.register') }}</a>
            @else
                <a href="{{ route('user.profile') }}" class="hover:text-navbar-hover">
                    <i class="bi bi-person mr-2"></i> {{ __('public/interface.user_profile') }}
                </a>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-navbar-hover">
                        <i class="bi bi-speedometer2 mr-2"></i> {{ __('public/interface.dashboard') }}
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="hover:text-navbar-hover">
                        <i class="bi bi-box-arrow-right mr-2"></i> {{ __('public/interface.logout') }}
                    </button>
                </form>
            @endguest
        </div>
    </div>
</nav>
