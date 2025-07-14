<div x-data="{ isOpen: false }" x-on:open-version-slider.window="isOpen = true"
    x-on:close-version-slider.window="isOpen = false" x-cloak>
    <div x-show="isOpen" @click="isOpen = false; $wire.set('isOpen', false)"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40"></div>

    <div class="fixed top-0 right-0 h-full w-full max-w-full sm:max-w-sm md:max-w-md lg:max-w-lg
 bg-white dark:bg-gray-800 shadow-2xl border-l border-gray-200 dark:border-gray-700 z-50 transform transition-transform duration-300 ease-in-out"
        :class="{ 'translate-x-0': isOpen, 'translate-x-full': !isOpen }">
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <x-lucide-history class="text-green-900 h-5 w-5 mr-1" />
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Version History</h2>
                </div>
                <button @click="isOpen = false; $wire.close()" class="p-2 text-gray-500 hover:text-gray-700">
                    <x-lucide-x class="h-5 w-5" />
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                @foreach ($versions as $version)
                    <div wire:key="version-{{ $version->id }}">
                        <div @click.prevent="$wire.selectVersion({{ $version->id }})" wire:loading.attr="disabled"
                            class="p-4 rounded border cursor-pointer bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                    @if ($editingVersionId === $version->id)
                                        <div @click.stop>
                                            <input wire:model.defer="editingLabel" wire:keydown.enter="saveLabel"
                                                wire:keydown.escape="cancelEditing" type="text"
                                                class="w-full rounded border px-2 py-1 text-sm dark:bg-gray-700 dark:text-white"
                                                autofocus />
                                            <div class="mt-1 space-x-2">
                                                <button wire:click="saveLabel"
                                                    class="btn btn-sm btn-primary">Save</button>
                                                <button wire:click="cancelEditing"
                                                    class="btn btn-sm btn-secondary">Cancel</button>
                                            </div>
                                        </div>
                                    @else
                                        {{ $version->label }}
                                    @endif

                                </h3>

                                <div class="relative" @click.stop>
                                    <x-dropdown>
                                        <x-dropdown.item icon="document-duplicate" label="Duplicate"
                                            wire:click="duplicateVersion({{ $version->id }})" wire:loading.attr="disabled"  />

                                        <x-dropdown.item icon="pencil" label="Rename"
                                            wire:click="startEditingLabel({{ $version->id }})" wire:loading.attr="disabled" />

                                        @if ($version->is_published)
                                            <x-dropdown.item icon="x-circle" label="Unpublish"
                                                wire:click="unPublishVersion({{ $version->id }})" wire:loading.attr="disabled"/>
                                        @else
                                            <x-dropdown.item icon="arrow-up-tray" label="Publish"
                                                wire:click="publishVersion({{ $version->id }})" wire:loading.attr="disabled" />
                                        @endif

                                        <x-dropdown.item icon='trash' label="Delete"
                                            wire:click="deleteVersion({{ $version->id }})" wire:loading.attr="disabled"/>
                                    </x-dropdown>
                                </div>
                            </div>

                            @if ($editingVersionId != $version?->id)
                                <div class="text-sm text-gray-700 dark:text-gray-300">
                                    Status:
                                    @if ($version->is_published)
                                        <x-badge primary label="Published" />
                                    @else
                                        <x-badge amber label="Draft" />
                                    @endif
                                </div>

                                @if ($version->is_active)
                                    <span class="ml-2 text-xs text-green-700 dark:text-green-300">• Active</span>
                                @endif

                                <div class="text-xs text-gray-500 mt-2">
                                    Created: {{ \Carbon\Carbon::parse($version->generated_at)->format('M d, Y') }}
                                    <br>
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <button wire:click="createVersion"
                    class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 flex items-center justify-center">
                    <x-lucide-plus class="h-4 w-4 mr-2" />
                    Create New Version
                </button>
            </div>
        </div>
    </div>
</div>
