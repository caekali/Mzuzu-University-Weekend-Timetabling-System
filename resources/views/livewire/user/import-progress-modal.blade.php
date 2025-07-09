<x-modal-card id="userImportprogress" name="userImportprogress" title="Import Progress" x-close='$wire.closeModal()'>
    <div class="p-6   space-y-6">
        <div>
            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                <span>Processing Users</span>
                <span>{{ $progress['processed'] }} / {{ $progress['total'] }}</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-4">
                <div class="h-3 rounded-full transition-all duration-300 {{ $progress['isProcessing']
                    ? 'bg-green-600 dark:bg-green-500'
                    : ($progress['failed']
                        ? 'bg-amber-500 dark:bg-amber-400'
                        : 'bg-green-600 dark:bg-green-500') }}"
                    style="width: {{ $progress['total'] > 0 ? ($progress['processed'] / $progress['total']) * 100 : 0 }}%">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div
                    class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700">
                    <div class="flex items-center justify-center mb-1">
                        <x-lucide-check-circle class="h-4 w-4 text-green-600 dark:text-green-400 mr-1" />
                        <span class="text-sm font-medium text-green-800 dark:text-green-300">Success</span>
                    </div>
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ $progress['successful'] }}
                    </div>
                </div>

                <div
                    class="text-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-700">
                    <div class="flex items-center justify-center mb-1">
                        <x-icon name="x-circle" class="h-4 w-4 text-red-600 dark:text-red-400 mr-1" />
                        <span class="text-sm font-medium text-red-800 dark:text-red-300">Failed</span>
                    </div>
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                        {{ $progress['failed'] }}
                    </div>
                </div>
            </div>
        </div>


        @if ($progress['failed'] > 0)
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Import Errors</h3>
                    <x-button secondary wire:click="downloadErrors" wire:loading.attr="disabled"
                        label='Download failed imports'>
                        <x-slot:prepend>
                            <x-lucide-download class="h-4 w-4 mr-1" />
                        </x-slot:prepend>
                    </x-button>
                </div>
                <div class="max-h-64 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    @foreach ($importedUsers as $user)
                        @if (!empty($user['errors']))
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="mb-1 text-sm font-semibold text-red-700 dark:text-red-400">
                                    Row {{ $user['row_number'] ?? '?' }}: {{ $user['first_name'] ?? '?' }}
                                    {{ $user['last_name'] ?? '' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                    {{ $user['email'] ?? 'No Email' }}
                                </div>
                                @foreach ($user['errors'] as $err)
                                    <div
                                        class="text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded mb-1">
                                        {{ $err }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-modal-card>
