@extends('layouts.public')

@section('content')
<section class="my-16">
    <!-- Titre principal -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-title text-heading">{{ __('public/interface.contact_us') }}</h1>
        <p class="text-body text-sm mt-2 italic">{{ __('public/interface.contact_subtitle') }}</p>
    </div>

    <div class="container mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
        <!-- Formulaire de contact -->
        <div class="bg-white shadow-lg rounded-xl p-8 border-t-4 border-accent transform hover:scale-105 transition-transform duration-300">
            <h2 class="text-2xl font-title text-heading mb-4 text-center">{{ __('public/interface.send_us_message') }}</h2>
            <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-bold text-body">{{ __('public/interface.name') }}</label>
                    <input type="text" id="name" name="name" class="w-full mt-2 px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent bg-neutral" placeholder="{{ __('public/interface.name_placeholder') }}">
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-body">{{ __('public/interface.email') }}</label>
                    <input type="email" id="email" name="email" class="w-full mt-2 px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent bg-neutral" placeholder="{{ __('public/interface.email_placeholder') }}">
                </div>

                <div>
                    <label for="message" class="block text-sm font-bold text-body">{{ __('public/interface.message') }}</label>
                    <textarea id="message" name="message" rows="5" class="w-full mt-2 px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent bg-neutral" placeholder="{{ __('public/interface.message_placeholder') }}"></textarea>
                </div>

                <div>
                    <button type="submit" class="w-full px-4 py-3 bg-accent text-white font-bold rounded-lg shadow-lg hover:bg-cta transition">
                        {{ __('public/interface.send_message') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Informations de contact et Google Map -->
        <div class="relative">
            <div class="bg-gradient-to-br from-page via-white to-neutral shadow-xl rounded-xl p-8">
                <h2 class="text-2xl font-title text-heading mb-6 text-center">{{ __('public/interface.contact_info') }}</h2>
                <ul class="space-y-6">
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent mr-4 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l1-2m0 0l8-8 5 5-8 8m0 0l-1 2M4 18h16M4 6h16" />
                        </svg>
                        <span class="text-body">{{ __('public/interface.address') }}: <span class="font-bold">{{ $settings['site_address'] }}</span></span>
                    </li>
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent mr-4 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.5 4.5L17.5 15.5M4 6h16m-8 12h-4m4 0h4" />
                        </svg>
                        <span class="text-body">{{ __('public/interface.phone') }}: <span class="font-bold">{{ $settings['site_phone'] }}</span></span>
                    </li>
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent mr-4 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12V4m0 8v4m0-8l-4 4m0 0l-4-4" />
                        </svg>
                        <span class="text-body">{{ __('public/interface.email') }}: <span class="font-bold">{{ $settings['site_email'] }}</span></span>
                    </li>
                </ul>
            </div>

            <!-- Google Map -->
            <div class="mt-6">
                <iframe
                    width="100%"
                    height="300"
                    style="border:0; border-radius: 8px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);"
                    loading="lazy"
                    allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps/embed/v1/place?key={{ env('GOOGLE_MAPS_API_KEY') }}&q={{ urlencode($settings['site_address']) }}">
                </iframe>
            </div>
        </div>
    </div>
</section>
@endsection
