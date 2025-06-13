<div class="py-6" x-data='{showFilters: false}'>
    <div class="flex justify-between items-center mb-6 print:hidden">
        <h1 class="text-2xl font-bold text-gray-900">Timetable</h1>
        <button @click="showFilters = !showFilters"
            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <x-lucide-filter class="h-4 w-4 mr-1" />
            Filters
        </button>
    </div>


    <div x-show="showFilters" class="bg-white rounded-lg shadow-sm p-6 mb-6 print:hidden">
        <div class="flex justify-between items-center mb-4" x-cloak>
            <h2 class="text-lg font-medium text-gray-900">Filters</h2>
            <button @click="showFilters = false">
                <x-lucide-x class="h-5 w-5 text-gray-500 hover:text-gray-700" />
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-select label="Programme" placeholder="Select programme" :options="$programmes" option-label="name"
                option-value="id" wire:model.defer="selectedProgramme" />
            <x-select label="Lecturer" placeholder="Select lecturer" :options="$programmes" option-label="name"
                option-value="id" wire:model.defer="selectedLecturer" />
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-2 md:p-6 overflow-x-auto">
        <h2 class="text-lg font-medium text-gray-900 mb-4 px-2 ">
            Weekly Schedule
        </h2>
        <div class="min-w-full overflow-hidden print:min-w-0">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                <thead>
                    <tr>
                        <th
                            class="px-2 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                            Time
                        </th>

                        @foreach ($days as $day)
                            <th
                                class="px-2 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $day }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">

                    @foreach ($timeSlots as $slot)
                        <tr>
                            <td
                                class="border px-2 py-1 md:py-2 whitespace-nowrap text-xs md:text-sm text-gray-500 bg-gray-50 font-medium">
                                {{ \Carbon\Carbon::parse($slot['start'])->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($slot['end'])->format('H:i') }}
                            </td>
                            @foreach ($days as $day)
                                <td
                                    class="border text-sm px-1 md:px-2 py-1 md:py-2 align-top cursor-pointer hover:bg-gray-50">
                                    @php
                                        $cellEntries = $entries->filter(function ($entry) use ($day, $slot) {
                                            return $entry->day === $day && $entry->start_time === $slot['start'];
                                        });
                                    @endphp

                                    @if ($cellEntries->isNotEmpty())
                                        @foreach ($cellEntries as $entry)
                                            <div class="bg-blue-100 rounded p-1 mb-1">
                                                <div class="font-bold">{{ $entry->course->code }}</div>
                                                <div>{{ $entry->lecturer->user->first_name }}</div>
                                                <div class="text-xs text-gray-700">{{ $entry->venue->name }}</div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="h-16 w-full flex items-center justify-center text-gray-400 hover:text-gray-600 cursor-pointer"
                                            wire:click="openModal(null, '{{ $day }}', '{{ $slot['start'] }}', '{{ $slot['end'] }}')">
                                            <x-lucide-plus class="h-4 w-4" />
                                        </div>
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








{{-- <div>

       <div class="mb-4 flex space-x-4">
           <div>
               <label class="block text-sm font-medium">Filter by Programme:</label>
               <select wire:model.live="selectedProgramme" class="border rounded px-2 py-1 w-full">
                   <option value="">All</option>
                   @foreach ($programmes as $programme)
                       <option value="{{ $programme->id }}">{{ $programme->name }}</option>
                   @endforeach
               </select>
           </div>

           <div>
               <label class="block text-sm font-medium">Filter by Lecturer:</label>
               <select wire:model.live="selectedLecturer" class="border rounded px-2 py-1 w-full">
                   <option value="">All</option>
                   @foreach ($lecturers as $lecturer)
                       <option value="{{ $lecturer->id }}">{{ $lecturer->user->first_name }}</option>
                   @endforeach
               </select>
           </div>
       </div>

       <div class="overflow-x-auto">
           <table class="table-auto border-collapse w-full">
               <thead>
                   <tr class="bg-gray-200">
                       <th class="border px-4 py-2">Time</th>
                       
                   </tr>
               </thead>
               <tbody>
                   
               </tbody>
           </table>
       </div>
   </div> --}}
