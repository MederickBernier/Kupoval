@if (session('toast') || session('toasts'))
    <div class="fixed bottom-5 right-5 z-50 flex flex-col space-y-2 w-80">
        @if (session('toast'))
            <div x-data="{ show: true }"
                 x-init="setTimeout(() => show = false, 4000)"
                 x-show="show"
                 x-transition.duration.500ms
                 class="px-4 py-3 rounded-lg shadow-lg text-white flex items-center justify-between
                    {{ session('toast')['type'] === 'success' ? 'bg-green-500' : '' }}
                    {{ session('toast')['type'] === 'error' ? 'bg-red-500' : '' }}
                    {{ session('toast')['type'] === 'warning' ? 'bg-yellow-500' : '' }}"
            >
                <span>{{ session('toast')['message'] }}</span>
                <button @click="show = false" class="ml-4 text-white font-bold">&times;</button>
            </div>
        @endif

        @if (session('toasts'))
            @foreach (session('toasts') as $toast)
                <div x-data="{ show: true }"
                     x-init="setTimeout(() => show = false, 4000)"
                     x-show="show"
                     x-transition.duration.500ms
                     class="px-4 py-3 rounded-lg shadow-lg text-white flex items-center justify-between
                        {{ $toast['type'] === 'success' ? 'bg-green-500' : '' }}
                        {{ $toast['type'] === 'error' ? 'bg-red-500' : '' }}
                        {{ $toast['type'] === 'warning' ? 'bg-yellow-500' : '' }}"
                >
                    <span>{{ $toast['message'] }}</span>
                    <button @click="show = false" class="ml-4 text-white font-bold">&times;</button>
                </div>
            @endforeach
        @endif
    </div>
@endif
