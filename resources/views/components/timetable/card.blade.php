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

    $borderColor = $dayColors[$scheduleEntry->day] ?? 'border-gray-300';
@endphp

<div
    class=" bg-amber-700 p-4 rounded-lg shadow-md flex flex-col gap-2 w-full border-l-8 {{ $borderColor }} transition hover:shadow-md bg-white dark:bg-gray-800">
    <div class="flex gap-2 items-center">
        <x-lucide-notebook class="h-5 w-5 text-gray-900 dark:text-white" />
        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $scheduleEntry->course->code}} - {{ $scheduleEntry->course->name}}</p>
    </div>
    <div class="flex gap-2 items-center">
        <x-lucide-map-pin class="h-5 w-5 text-gray-900 dark:text-white" />
        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $scheduleEntry->venue->name }}</p>
    </div>
    <div class="flex gap-2 items-center">
        <x-lucide-clock class="h-5 w-5 text-gray-900 dark:text-white" />
        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $scheduleEntry->start_time }} - {{ $scheduleEntry->end_time }}</p>
    </div>
</div>
