<div class="py-6">
    <div x-data="{ showFilters: false }">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Full Timetable</h1>
            <x-button @click="showFilters = !showFilters">
                <x-slot:prepend>
                    <x-lucide-filter class="h-4 w-4 mr-1" />
                </x-slot:prepend>
                Filters
            </x-button>

        </div>
        <div x-show="showFilters" x-cloak
            class="bg-white dark:bg-gray-800  border border-gray-200 dark:border-gray-700 transition-colors duration-200 rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Filters</h2>
                <button @click="showFilters = false">
                    <x-lucide-x class="h-5 w-5 text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-600" />
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-select label="Programme" placeholder="Select programme" :options="$programmes" option-label="name"
                    option-value="id" wire:model.live="selectedProgramme" />
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
            Weekly Schedule
        </h2>
        <div class="min-w-full overflow-hidden print:min-w-0">
            <table
                class="min-w-full divide-y divide-gray-200 border border-gray-200 dark:border-gray-700 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-900">
                    <tr>
                        <th
                            class="px-2 py-3  text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">
                            Time
                        </th>
                        @foreach ($days as $day)
                            <th
                                class="px-2 py-3  text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ $day }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white  dark:bg-gray-800 divide-y divide-gray-200 ">

                    @foreach ($timeSlots as $slot)
                        <tr>
                            <td
                                class="border dark:border-gray-700 px-2 py-1 md:py-2 whitespace-nowrap text-xs md:text-sm text-gray-500 bg-gray-50 dark:bg-gray-800 font-medium">
                                {{ \Carbon\Carbon::parse($slot['start'])->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($slot['end'])->format('H:i') }}
                            </td>
                            @foreach ($days as $day)
                                <td
                                    class="border dark:border-gray-700 text-sm px-1 md:px-2 py-1 md:py-2 align-top cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                    @php
                                        $cellEntries = $entries->filter(function ($entry) use ($day, $slot) {
                                            return $entry->day === $day && $entry->start_time === $slot['start'];
                                        });
                                    @endphp

                                    @if ($cellEntries->isNotEmpty())
                                        @foreach ($cellEntries as $entry)
                                            <div class="bg-green-100 rounded p-1 mb-1"
                                                wire:click="openModal({{ $entry->id }}, '{{ $day }}', '{{ $slot['start'] }}', '{{ $slot['end'] }}')"
                                                title="Edit Schedule">
                                                <div class="font-bold flex items-center gap-2 ">
                                                    <x-lucide-book-open class="w-3 h-3" />
                                                    <span> {{ $entry->course->code }}</span>
                                                </div>
                                                <div class=" text-gray-700  flex items-center gap-2">
                                                    <x-lucide-user class="w-3 h-3" />
                                                    <span>{{ $entry->lecturer->user->first_name . ' ' . $entry->lecturer->user->last_name }}</span>
                                                </div>

                                                <div class="text-gray-600 flex items-center gap-2">
                                                    <x-lucide-map-pin class="w-3 h-3" />
                                                    <span>{{ $entry->venue->name }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="h-8 w-full flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer"
                                        wire:click="openModal(null, '{{ $day }}', '{{ $slot['start'] }}', '{{ $slot['end'] }}')"
                                        title="Add Schedule">
                                        <x-lucide-plus class="h-4 w-4" />
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <livewire:timetable.schedule-modal />
</div>
