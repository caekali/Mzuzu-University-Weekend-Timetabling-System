<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center mb-4">
            <x-icon name="{{ $icon }}" class="w-6 h-6 text-{{ $iconColor }}-600 mr-3" />
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
        </div>
        <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $message }}</p>
        <div class="flex justify-end gap-3">
            <button wire:click="$emit('cancelConfirmation')"
                class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                Cancel
            </button>
            <button wire:click="$emit('confirmAction')"
                class="px-4 py-2 rounded-lg text-white font-medium bg-{{ $iconColor }}-600 hover:bg-{{ $iconColor }}-700">
                Confirm
            </button>
        </div>
    </div>
</div>
