@props(['scheduleEntry'])

@php
    $dayColors = [
        'Monday' => 'border-blue-500',
        'Tuesday' => 'border-green-500',
        'Wednesday' => 'border-yellow-500',
        'Thursday' => 'border-purple-500',
        'Friday' => 'border-pink-500',
        'Saturday' => 'border-red-500',
        'Sunday' => 'border-gray-500',
    ];

    $borderColor = $dayColors[$scheduleEntry['day']] ?? 'border-gray-300';
@endphp

<div
    class="w-full bg-white dark:bg-gray-800 border-l-4 {{ $borderColor }} p-4 rounded-xl shadow transition hover:shadow-lg">
    <div class="space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="flex items-center gap-2">
                <x-lucide-book-open class="h-5 w-5 text-green-600 dark:text-green-400" />
                <div>
                    <p class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $scheduleEntry['course_code'] }}</p>
                    <p class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $scheduleEntry['course_name'] }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:ml-4">
                <x-lucide-clock class="h-5 w-5 text-gray-500 dark:text-gray-300" />
                <p class="text-sm sm:text-base text-gray-700 dark:text-gray-200">
                    {{ $scheduleEntry['start_time'] }} - {{ $scheduleEntry['end_time'] }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-lucide-map-pin class="h-5 w-5 text-blue-600 dark:text-blue-400" />
            <p class="text-sm sm:text-base font-medium text-gray-800 dark:text-white">
                {{ $scheduleEntry['venue'] }}
            </p>
        </div>
        @if (session('current_role') == 'Student')
            <div class="flex items-center gap-2">
                <x-lucide-user class="h-5 w-5 text-purple-600 dark:text-purple-400" />
                <p class="text-sm sm:text-base font-medium text-gray-800 dark:text-white">
                    {{ $scheduleEntry['lecturer'] }}
                </p>
            </div>
        @endif
    </div>
</div>
