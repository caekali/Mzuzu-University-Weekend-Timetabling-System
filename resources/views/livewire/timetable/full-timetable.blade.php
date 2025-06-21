<div class="py-6">
    <div x-data="{ showFilters: true }">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Full Weekly Timetable</h1>

            @if (session('current_role') === 'Admin')
                <div class="flex flex-col sm:flex-row   gap-2 sm:items-end">
                    <x-select label="Version" placeholder="Select version" :options="$scheduleVersions" option-label="label"
                        option-value="id" wire:model.live="selectedVersionId" class="min-w-[200px]" />
                    <x-button label="Publish" wire:click="publishSelectedVersion" :disabled="!$selectedVersionId"
                        class="whitespace-nowrap">
                        <x-slot:prepend>
                            <x-lucide-upload class="h-4 w-4 mr-2" />
                        </x-slot:prepend>
                    </x-button>
                </div>
            @endif
        </div>

        {{-- <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Full Weekly Timetable</h1>
        </div> --}}
        <div x-show="showFilters" x-cloak
            class="bg-white dark:bg-gray-800  border border-gray-200 dark:border-gray-700 transition-colors duration-200 rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Filters</h2>

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
            Timetable - Use the filters to view timetable entries. </h2>
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
                                <td class="border dark:border-gray-700 px-1 md:px-2 py-1 md:py-2 align-top w-40">
                                    @php
                                        $cellEntries = $entries->filter(function ($entry) use ($day, $slot) {
                                            return $entry->day === $day && $entry->start_time === $slot['start'];
                                        });
                                    @endphp

                                    @if ($cellEntries->isNotEmpty())
                                        @foreach ($cellEntries as $entry)
                                            <div class="bg-green-100 rounded p-1 mb-1 w-full min-h-[80px]">
                                                <div
                                                    wire:click="openModal({{ $entry->id }}, '{{ $day }}', '{{ $slot['start'] }}', '{{ $slot['end'] }}')">
                                                    <div class="font-bold flex items-center gap-2">
                                                        <x-lucide-book-open class="w-3 h-3" />
                                                        <span>{{ $entry->course->code }}</span>
                                                    </div>
                                                    <div class="text-gray-700 flex items-center gap-2">
                                                        <x-lucide-user class="w-3 h-3" />
                                                        <span>{{ $entry->lecturer->user->first_name . ' ' . $entry->lecturer->user->last_name }}</span>
                                                    </div>
                                                    <div class="text-gray-600 flex items-center gap-2">
                                                        <x-lucide-map-pin class="w-3 h-3" />
                                                        <span>{{ $entry->venue->name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        {{-- Empty cell with fixed height to preserve spacing --}}
                                        <div class="min-h-[80px] w-36"></div>
                                    @endif
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
