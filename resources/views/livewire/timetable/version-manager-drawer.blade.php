<div x-data="{ open: false }" x-on:open-version-drawer.window="open = true; document.body.classList.add('overflow-hidden')"
    x-on:close-version-drawer.window="open = false; document.body.classList.remove('overflow-hidden')"
    x-init="window.addEventListener('keydown', e => {
        if (open && e.key === 'Escape') {
            open = false;
            document.body.classList.remove('overflow-hidden');
        }
    });" x-cloak>

    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-40 z-40"
        x-on:click="open = false; document.body.classList.remove('overflow-hidden')"></div>

    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
        @click.away="open = false; document.body.classList.remove('overflow-hidden')"
        class="fixed inset-y-0 right-0 bg-white dark:bg-gray-800 w-full max-w-md z-50 shadow-lg  text-gray-900 dark:text-gray-100 border-l border-gray-200 dark:border-gray-700 transition transform">


        
        <div class="h-16 px-6 flex justify-between items-center border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Timetable Versions</h2>
            <button x-on:click="open = false; document.body.classList.remove('overflow-hidden')"
                class=" text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-4 space-y-4 overflow-y-auto max-h-[calc(100vh-5rem)]">
            @foreach ($scheduleVersions as $version)
                <div
                    class="flex items-center justify-between gap-4 p-3 bg-gray-50 dark:bg-gray-800 border dark:border-gray-700 rounded-lg shadow-sm transition hover:bg-gray-100 dark:hover:bg-gray-700">
                    @if ($editingVersionId === $version->id)
                        <x-input wire:model.defer="editableLabel" class="w-full" autofocus />
                    @else
                        <div class="flex items-center gap-3">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $version->label }}
                            </div>
                            @if ($version->is_published)
                                <x-badge primary label=" Published" />
                            @endif
                        </div>
                    @endif
                    <div class="flex items-center gap-2">
                        @if ($editingVersionId === $version->id)
                            <x-mini-button rounded flat icon="check" wire:click="saveLabel({{ $version->id }})"
                                primary />
                        @else
                            <x-mini-button rounded flat icon="pencil"
                                wire:click="startEditing({{ $version->id }}, '{{ $version->label }}')" primary />
                        @endif

                        <x-mini-button rounded flat icon="eye" wire:click="viewVersion({{ $version->id }})" x-on:click="open = false; document.body.classList.remove('overflow-hidden')"
                            primary />

                        @if ($version->is_published)
                            <x-mini-button rounded flat icon="x-circle"
                                wire:click="unpublishVersion({{ $version->id }})" title="Unpublish"
                                interaction="secondary" />
                        @else
                            <x-mini-button rounded flat icon="arrow-up-tray"
                                wire:click="publishVersion({{ $version->id }})" title="Publish" primary />
                        @endif

                        <x-mini-button rounded flat icon="trash" wire:click="deleteVersion({{ $version->id }})"
                            interaction="negative" />
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</div>
