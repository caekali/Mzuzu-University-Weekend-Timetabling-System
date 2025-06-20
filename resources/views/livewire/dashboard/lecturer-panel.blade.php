@php
    use Carbon\Carbon;

    $now = Carbon::now();
    $hour = $now->hour;

    $greeting = match (true) {
        $hour < 12 => 'Good morning',
        $hour < 18 => 'Good afternoon',
        default => 'Good evening',
    };

    $formattedDate = $now->format('l, F j, Y'); // Saturday, May 24, 2025
@endphp


<div class="flex flex-col h-full">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $greeting }}, {{ Auth::user()->first_name }}!
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-100">{{ $formattedDate }}</p>
        <p class="text-sm text-gray-600 dark:text-gray-100 mt-1">Here are your schedules for today.</p>
    </div>

    <h3 class="text-lg font-semibold text-gray-800 mb-3 dark:text-white" >Today's Schedules</h3>

    @if ($toscheduleDayEntries && count($toscheduleDayEntries) > 0)
        <!-- Scrollable container takes remaining height -->
        <div class="flex-1 overflow-y-auto space-y-4">
            @foreach ($toscheduleDayEntries as $scheduleEntry)
                <x-timetable.card :scheduleEntry="$scheduleEntry" />
            @endforeach
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-4">
            <p class="text-gray-600 dark:text-white">You have no scheduled classes today.</p>
        </div>
    @endif

    <div class=" bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md mt-6">
        <a href="{{ route('timetable') }}" class="text-green-600 font-medium hover:underline">
            View Full Weekly Schedule →
        </a>
    </div>
</div>
