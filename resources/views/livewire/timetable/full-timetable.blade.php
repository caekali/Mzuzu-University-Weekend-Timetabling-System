<div class="py-6">
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('conflictState', {
                highlightedEntryIds: [],
            });

            window.addEventListener('highlight-conflicts', event => {
                Alpine.store('conflictState').highlightedEntryIds = event.detail[0].entryIds ?? [];
            });
        });
    </script>

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <div class="flex items-start sm:items-center">
            <x-lucide-calendar class="h-6 w-6 text-green-600 dark:text-green-400 mr-3 mt-1 sm:mt-0" />
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Timetable</h1>
                <div class="flex flex-wrap items-center mt-1 gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <span>
                        Current: {{ $currentVersion?->label ?? '—' }}
                    </span>
                    @if ($currentVersion)
                        @if ($currentVersion->is_published)
                            <x-badge primary label="Published" />
                        @else
                            <x-badge amber label="Draft" />
                        @endif
                    @else
                        <x-badge amber label="Not Available" />
                    @endif
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            <x-button secondary wire:click="$toggle('showFilters')">
                <x-slot:prepend>
                    <x-lucide-filter class="h-4 w-4" />
                </x-slot:prepend>
                Filters
            </x-button>
            <x-dropdown>
                <x-slot name="trigger">
                    <x-button secondary>
                        <x-slot:prepend>
                            <x-lucide-download class="h-4 w-4" />
                        </x-slot:prepend>
                        Export
                    </x-button> </x-slot>

                <x-dropdown.item label="PDF" wire:click="export('pdf')" />
                <x-dropdown.item label="Excel" wire:click="export('excel')" />
            </x-dropdown>
            @if (session('current_role') === 'Admin')
                <x-button secondary wire:click="$dispatch('openVersionSlider')">
                    <x-slot:prepend>
                        <x-lucide-history class="h-4 w-4" />
                    </x-slot:prepend>
                    Versions
                </x-button>
            @endif
        </div>
    </div>

    <div x-data="{ open: @entangle('showFilters') }" x-show="open" x-cloak>
        <div
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 transition-colors duration-200 rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Filters</h2>
                <button @click="open = false; $wire.set('showFilters', false)"
                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none"
                    aria-label="Close filters">
                    <x-lucide-x class="h-5 w-5" />
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-select label="Programme" placeholder="Select programme" :options="$programmes"
                    wire:model.live="selectedProgramme" option-label="name" option-value="id" />
                <x-select label="Level" placeholder="Select level" :options="$levels" wire:model.live="selectedLevel" />
                <x-select label="Lecturer" placeholder="Select lecturer" :options="$lecturers" option-label="name"
                    option-value="id" wire:model.live="selectedLecturer" />
                <x-select label="Venue" placeholder="Select venue" :options="$venues" option-label="name"
                    option-value="id" wire:model.live="selectedVenue" />
            </div>
        </div>
    </div>

    <div
        class="bg-white dark:bg-gray-800  border border-gray-200 dark:border-gray-700 transition-colors duration-200
 rounded-lg shadow-sm p-2 md:p-6 overflow-x-auto">
        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4 px-2 ">
            Timetable - (Compacted) </h2>
        <div class="min-w-full overflow-x-auto">
            <table
                class="min-w-full divide-y divide-gray-200 border border-gray-200 dark:border-gray-700 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-900">
                    <tr>
                        <th
                            class="px-2 py-3  text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">
                            Time\
                            <br />
                            Day
                        </th>
                        @foreach ($timeSlots as $slot)
                            <th
                                class="px-2 py-3  text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ \Carbon\Carbon::parse($slot['start'])->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($slot['end'])->format('H:i') }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white  dark:bg-gray-800 divide-y divide-gray-200 ">
                    @foreach ($days as $day)
                        <tr>
                            <td
                                class="border dark:border-gray-700 px-2 py-1 md:py-2 text-xs md:text-sm text-gray-500 bg-gray-50 dark:bg-gray-800 font-medium w-20">
                                {{ $day }}
                            </td>

                            @foreach ($timeSlots as $slot)
                                @if ($slot['type'] === 'break')
                                    <td
                                        class="border dark:border-gray-700 px-2 py-2 bg-yellow-50 dark:bg-yellow-900/20 text-center text-yellow-800 dark:text-yellow-300 font-semibold text-sm">
                                        <div
                                            class="min-h-[80px] flex items-center justify-center flex-col dark:text-white">
                                            Break
                                        </div>
                                    </td>
                                @else
                                    <td class="border dark:border-gray-700 px-1 md:px-2 py-1 md:py-2 align-top w-40">
                                        @php
                                            $cellEntries = $entries->filter(function ($entry) use ($day, $slot) {
                                                return $entry->day === $day && $entry->start_time === $slot['start'];
                                            });
                                        @endphp

                                        @if ($cellEntries->isNotEmpty())
                                            @foreach ($cellEntries as $entry)
                                                @if (session('current_role') == 'Admin')
                                                    <div wire:key="entry-{{ $entry->id }}" class="space-y-2 mb-2">
                                                        <div x-data="{}"
                                                            x-bind:class="Alpine.store('conflictState')?.highlightedEntryIds.includes(
                                                                    {{ $entry->id }}) ?
                                                                'bg-red-100 border-red-400 dark:bg-red-900/30 dark:border-red-700' :
                                                                'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-700'"
                                                            class="min-h-[80px] hover:cursor-pointer p-1 md:p-2 rounded-lg border group transition-all duration-200 hover:shadow-md"
                                                            wire:click="openModal({{ $entry->id }}, '{{ $day }}', '{{ $slot['start'] }}', '{{ $slot['end'] }}')">
                                                            <div
                                                                class="font-bold text-green-900 dark:text-green-300 flex items-center justify-between">
                                                                <div class="flex items-center">
                                                                    <x-lucide-book-open
                                                                        class="h-3 w-3 mr-1 flex-shrink-0" />
                                                                    <span>{{ $entry->course_code }}</span>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="text-green-800 dark:text-green-200 text-xs md:text-sm">
                                                                {{ $entry->course_name }}
                                                            </div>
                                                            <div
                                                                class="text-gray-600 dark:text-gray-400 flex items-center mt-1 text-xs">
                                                                <x-lucide-user class="h-3 w-3 mr-1 flex-shrink-0" />
                                                                <span>{{ $entry->lecturer }}</span>
                                                            </div>
                                                            <div
                                                                class="text-gray-600 dark:text-gray-400 flex items-center text-xs">
                                                                <x-lucide-map-pin class="w-3 h-3  mr-1 flex-shrink-0" />
                                                                <span>{{ $entry->venue }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div
                                                        class="  mb-2 min-h-[80px]  p-1 md:p-2 rounded-lg border group transition-all duration-200 hover:shadow-md bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-700">
                                                        <div
                                                            class="font-bold text-green-900 dark:text-green-300 flex items-center justify-between">
                                                            <div class="flex items-center">
                                                                <x-lucide-book-open
                                                                    class="h-3 w-3 mr-1 flex-shrink-0" />
                                                                <span>{{ $entry->course_code }} </span>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="text-green-800 dark:text-green-200 text-xs md:text-sm">
                                                            {{ $entry->course_name }}</div>
                                                        <div
                                                            class="text-gray-600 dark:text-gray-400 flex items-center mt-1 text-xs">
                                                            <x-lucide-user class="h-3 w-3 mr-1 flex-shrink-0" />
                                                            <span>{{ $entry->lecturer }}</span>
                                                        </div>
                                                        <div
                                                            class="text-gray-600 dark:text-gray-400 flex items-center text-xs">
                                                            <x-lucide-map-pin class="w-3 h-3  mr-1 flex-shrink-0" />
                                                            <span>{{ $entry->venue }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            {{-- Empty cell  to preserve spacing --}}
                                            <div class="min-h-[80px] w-36"></div>
                                        @endif
                                        @if (session('current_role') == 'Admin')
                                            <div class="h-8 w-full flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer"
                                                wire:click="openModal(null, '{{ $day }}', '{{ $slot['start'] }}', '{{ $slot['end'] }}')"
                                                title="Add Schedule">
                                                <x-lucide-plus class="h-4 w-4" />
                                            </div>
                                        @endif

                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <livewire:timetable.version-manager-drawer :current-version-id="$currentVersionId"
        wire:key="version-manager-drawer-{{ $currentVersionId }}" />

    <livewire:timetable.schedule-modal :version-id="$currentVersionId" wire:key="modal-{{ $currentVersionId }}" />
</div>
