<div class="py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Timetable</h1>

        <div class="flex justify-end gap-3">
            @if ($publishedVersion)
                <x-button icon="arrow-down-tray" label="Export PDF" wire:click="exportToPdf" />
            @endif
        </div>

    </div>
    <div
        class="bg-white dark:bg-gray-800  border border-gray-200 dark:border-gray-700 transition-colors duration-200
 rounded-lg shadow-sm p-2 md:p-6 overflow-x-auto">
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
                                class="px-2 py-3  text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ \Carbon\Carbon::parse($slot['start'])->format('H:i') }} <br /> - <br />
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
                                            return $entry['day'] === $day && $entry['start_time'] === $slot['start'];
                                        });
                                    @endphp

                                    @if ($cellEntries->isNotEmpty())
                                        @foreach ($cellEntries as $entry)
                                            <div
                                                class="mb-2 min-h-[80px]  p-1 md:p-2 rounded-lg border group transition-all duration-200 hover:shadow-md bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-700">
                                                <div
                                                    class="font-bold text-green-900 dark:text-green-300 flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <x-lucide-book-open class="h-3 w-3 mr-1 flex-shrink-0" />
                                                        <span>{{ $entry['course_code'] }} </span>
                                                    </div>
                                                </div>
                                                <div class="text-green-800 dark:text-green-200 text-xs md:text-sm">
                                                    {{ $entry['course_name'] }}</div>
                                                <div
                                                    class="text-gray-600 dark:text-gray-400 flex items-center mt-1 text-xs">
                                                    <x-lucide-user class="h-3 w-3 mr-1 flex-shrink-0" />
                                                    <span>{{ $entry['lecturer'] }}</span>
                                                </div>
                                                <div class="text-gray-600 dark:text-gray-400 flex items-center text-xs">
                                                    <x-lucide-map-pin class="w-3 h-3  mr-1 flex-shrink-0" />
                                                    <span>{{ $entry['venue'] }}</span>
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
</div>
