<div
    x-data="{ show: @entangle('show') }"
    x-show="show"
    x-transition.opacity.duration.500ms
    @auto-hide.window="setTimeout(() => show = false, 4000)"
    class="fixed top-5 right-5 z-50 px-4 py-3 rounded-lg shadow-lg text-white"
    :class="{'bg-green-600': @js($type) === 'success', 'bg-red-600': @js($type) === 'error'}"
    style="display: none;"
>
    <p>{{ $message }}</p>
    <button @click="show = false" class="ml-4 font-bold">✖</button>
</div>
