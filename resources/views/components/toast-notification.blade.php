<div x-data="toastHandler()" class="fixed top-5 right-5 space-y-4 z-50">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible" x-transition
             class="flex items-center px-4 py-3 rounded-lg shadow-lg text-white"
             :class="{
                 'bg-green-500': toast.type === 'success',
                 'bg-red-500': toast.type === 'error',
                 'bg-yellow-500': toast.type === 'warning'
             }">

            <i class="mr-3 text-lg"
               :class="{
                   'bi bi-check-circle': toast.type === 'success',
                   'bi bi-x-circle': toast.type === 'error',
                   'bi bi-exclamation-circle': toast.type === 'warning'
               }"></i>

            <span x-text="toast.message"></span>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('toastHandler', () => ({
        toasts: [],
        addToast(message, type = 'success') {
            let id = Date.now();
            this.toasts.push({ id, message, type, visible: true });

            setTimeout(() => {
                this.toasts = this.toasts.filter(toast => toast.id !== id);
            }, 4000);
        }
    }));
});
</script>
