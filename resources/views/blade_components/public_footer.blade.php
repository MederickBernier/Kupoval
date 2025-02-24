<div class="flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-6 mt-6">
    <!-- Social Media Links -->
    <div class="flex space-x-4">
        @php
            $socialLinks = session('social_links', collect())->filter(fn($url) => !empty($url));
        @endphp

        @foreach ($socialLinks as $key => $url)
            <a href="{{ $url }}" target="_blank" class="text-gray-600 hover:text-accent transition-all duration-200 text-xl">
                @switch($key)
                    @case('social_facebook') <i class="bi bi-facebook"></i> @break
                    @case('social_twitter') <i class="bi bi-twitter"></i> @break
                    @case('social_instagram') <i class="bi bi-instagram"></i> @break
                    @case('social_tiktok') <i class="bi bi-tiktok"></i> @break
                    @case('social_youtube') <i class="bi bi-youtube"></i> @break
                    @case('social_linkedin') <i class="bi bi-linkedin"></i> @break
                @endswitch
            </a>
        @endforeach
    </div>

    <!-- Language Switcher (Links + Dropdown) -->
    @php
        $availableLanguages = ['frca' => '🇨🇦 Français (CA)', 'enca' => '🇨🇦 English (CA)'];
        $currentLocale = session('locale', config('app.locale'));
    @endphp

    <div class="flex items-center space-x-4">
        <!-- Quick Links (Shown for up to 3 languages) -->
        <div class="hidden md:flex space-x-3">
            @foreach ($availableLanguages as $code => $label)
                @if (count($availableLanguages) <= 3) {{-- Only show links if 3 or fewer languages exist --}}
                    <form action="{{ route('lang.switch') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="lang" value="{{ $code }}">
                        <button type="submit" class="text-gray-600 hover:text-accent transition-all duration-200 {{ $currentLocale === $code ? 'font-bold underline' : '' }}">
                            {{ $label }}
                        </button>
                    </form>
                @endif
            @endforeach
        </div>

        <!-- Dropdown (Always Visible for More Languages, Hidden if Few) -->
        @if (count($availableLanguages) > 3)
            <form action="{{ route('lang.switch') }}" method="POST">
                @csrf
                <div class="relative inline-block">
                    <select name="lang" onchange="this.form.submit()" class="appearance-none bg-white border border-gray-300 rounded-md px-4 py-2 text-sm shadow-sm focus:ring focus:ring-accent focus:outline-none cursor-pointer">
                        @foreach ($availableLanguages as $code => $label)
                            <option value="{{ $code }}" {{ $currentLocale === $code ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute top-0 right-2 h-full flex items-center pointer-events-none">
                        <i class="bi bi-chevron-down text-gray-500"></i>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>
