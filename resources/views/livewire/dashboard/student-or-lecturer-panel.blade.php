@php
    use Carbon\Carbon;

    $now = Carbon::now();
    $hour = $now->hour;

    $greeting = match (true) {
        $hour < 12 => 'Good morning',
        $hour < 18 => 'Good afternoon',
        default => 'Good evening',
    };

    $formattedDate = $now->format('l, F j, Y');
@endphp

<div class="flex flex-col h-full overflow-y-auto py-6 px-4">
    <div class="mb-2">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $greeting }}, {{ Auth::user()->first_name }}!
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-100">{{ $formattedDate }}</p>
        <p class="text-sm text-gray-600 dark:text-gray-100 mt-1">Here are your schedules.</p>
    </div>

    @if ($publishedVersion)
        <div class="sticky top-0 z-20">
            <div class="flex flex-wrap gap-2 sm:gap-4 px-2 sm:px-4">
                @foreach ($days as $day)
                    <button wire:click="loadEntriesForDay('{{ $day }}')"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200
                            {{ $selectedDay === $day
                                ? 'bg-green-600 text-white shadow'
                                : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-green-100 dark:hover:bg-green-600' }}">
                        {{ $day }}
                    </button>
                @endforeach
            </div>
        </div>


        @if (count($allDayEntries) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                @foreach ($allDayEntries as $entry)
                    <x-timetable.card :scheduleEntry="$entry" />
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mt-4">
                <p class="text-gray-600 dark:text-white">You have no scheduled classes on {{ $selectedDay }}.</p>
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <p class="text-gray-600 dark:text-white">Timetable has not been published yet.</p>
        </div>
    @endif

</div>
