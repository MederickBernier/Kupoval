<div x-data="toastHandler()"
    x-on:show-toast.window="addToast($event.detail.message, $event.detail.type)"
    class="fixed top-5 right-5 space-y-4 z-50">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-full"
            class="flex items-center px-4 py-3 rounded-lg shadow-lg text-white max-w-sm"
            :class="{
                 'bg-green-500 border-l-4 border-green-700': toast.type === 'success',
                 'bg-red-500 border-l-4 border-red-700': toast.type === 'error',
                 'bg-yellow-500 border-l-4 border-yellow-700': toast.type === 'warning',
                 'bg-blue-500 border-l-4 border-blue-700': toast.type === 'info'
             }">

            <i class="mr-3 text-lg flex-shrink-0"
                :class="{
                   'bi bi-check-circle-fill': toast.type === 'success',
                   'bi bi-x-circle-fill': toast.type === 'error',
                   'bi bi-exclamation-triangle-fill': toast.type === 'warning',
                   'bi bi-info-circle-fill': toast.type === 'info'
               }"></i>

            <span x-text="toast.message" class="text-sm font-medium"></span>

            <!-- Close button -->
            <button @click="removeToast(toast.id)"
                class="ml-auto flex-shrink-0 text-white hover:text-gray-200 transition-colors">
                <i class="bi bi-x text-lg"></i>
            </button>
        </div>
    </template>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('toastHandler', () => ({
            toasts: [],

            addToast(message, type = 'success') {
                let id = Date.now();
                this.toasts.push({
                    id,
                    message,
                    type,
                    visible: true
                });

                // Auto-remove after 4 seconds
                setTimeout(() => {
                    this.removeToast(id);
                }, 4000);
            },

            removeToast(id) {
                this.toasts = this.toasts.filter(toast => toast.id !== id);
            }
        }));

        // Global toast function for easy access
        window.showToast = function(message, type = 'success') {
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: {
                    message,
                    type
                }
            }));
        };
    });

    // Listen for Livewire events
    document.addEventListener('livewire:init', () => {
        Livewire.on('showToast', (event) => {
            const {
                message,
                type
            } = event;
            showToast(message, type);
        });
    });
</script>