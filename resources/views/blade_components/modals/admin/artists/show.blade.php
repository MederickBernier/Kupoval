<div x-cloak x-show="openShowArtistModal"
     class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
     x-transition.opacity @keydown.window.escape="openShowArtistModal = false"
     @click.away="openShowArtistModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-[80vh] overflow-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">{{ __('admin/artists.view_title') }}</h2>
            <button @click="openShowArtistModal = false" class="text-gray-600 hover:text-gray-900">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Artist Details -->
        <div class="text-center">
            <!-- Profile Picture -->
            <img x-bind:src="selectedArtist.photo ? '{{ asset('') }}' + selectedArtist.photo : '{{ asset('images/default-profile.png') }}'"
                 alt="Artist Photo"
                 class="w-24 h-24 rounded-full object-cover mx-auto mb-3 shadow-lg">

            <!-- Name -->
            <h3 class="text-lg font-semibold" x-text="selectedArtist.name"></h3>

            <!-- Bio with Read More / Less Toggle -->
            <div class="relative mt-3 text-gray-600 text-sm">
                <div class="max-h-24 overflow-hidden relative" x-ref="bioContainer">
                    <p class="leading-relaxed" x-html="selectedArtist.bio.replace(/\n/g, '<br>')"></p>
                </div>

                <!-- Expand Button -->
                <button @click="showFullBio = !showFullBio"
                        class="text-blue-500 hover:underline mt-2"
                        x-text="showFullBio ? '{{ __('admin/artists.read_less') }}' : '{{ __('admin/artists.read_more') }}'"
                        x-show="selectedArtist.bio.length > 150">
                </button>
            </div>

            <!-- Contact Details -->
            <div class="mt-4 space-y-2 text-sm">
                <p class="text-gray-700">
                    <i class="bi bi-envelope-fill text-blue-500"></i>
                    <a :href="'mailto:' + selectedArtist.email"
                       class="text-blue-600 hover:underline" x-text="selectedArtist.email"></a>
                </p>
                <p class="text-gray-700" x-show="selectedArtist.website">
                    <i class="bi bi-globe text-green-500"></i>
                    <a :href="selectedArtist.website" target="_blank"
                       class="text-green-600 hover:underline" x-text="selectedArtist.website"></a>
                </p>
            </div>

            <!-- Social Media Links -->
            <div class="flex justify-center space-x-3 mt-4">
                <a x-show="selectedArtist.facebook"
                   :href="selectedArtist.facebook"
                   target="_blank"
                   class="text-blue-600 hover:text-blue-800">
                    <i class="bi bi-facebook text-2xl"></i>
                </a>
                <a x-show="selectedArtist.twitter"
                   :href="selectedArtist.twitter"
                   target="_blank"
                   class="text-blue-400 hover:text-blue-600">
                    <i class="bi bi-twitter text-2xl"></i>
                </a>
                <a x-show="selectedArtist.instagram"
                   :href="selectedArtist.instagram"
                   target="_blank"
                   class="text-pink-500 hover:text-pink-700">
                    <i class="bi bi-instagram text-2xl"></i>
                </a>
                <a x-show="selectedArtist.tiktok"
                   :href="selectedArtist.tiktok"
                   target="_blank"
                   class="text-black hover:text-gray-800">
                    <i class="bi bi-tiktok text-2xl"></i>
                </a>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-end space-x-2">
            <button @click="openShowArtistModal = false"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                {{ __('admin/artists.close') }}
            </button>
            <a :href="selectedArtist.editUrl"
               class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                {{ __('admin/artists.edit') }}
            </a>
        </div>
    </div>
</div>

<!-- Alpine.js for Read More / Less -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('showArtistModal', () => ({
        openShowArtistModal: false,
        selectedArtist: { id: '', name: '', bio: '', photo: '', email: '', website: '', facebook: '', twitter: '', instagram: '', tiktok: '', editUrl: '' },
        showFullBio: false,

        setShowArtist(artist, editUrl) {
            this.selectedArtist = { ...artist, editUrl };
            this.openShowArtistModal = true;
            this.showFullBio = false; // Reset read more state
        }
    }));
});
</script>
