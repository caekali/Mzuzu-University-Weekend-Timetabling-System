@props(['schedule'])

@php
    $dayColors = [
        'Mon' => 'border-blue-500',
        'Tue' => 'border-green-500',
        'Wed' => 'border-yellow-500',
        'Thurs' => 'border-purple-500',
        'Fri' => 'border-pink-500',
        'Sat' => 'border-red-500',
        'Sun' => 'border-gray-500',
    ];

    $borderColor = $dayColors[$schedule->day] ?? 'border-gray-300';
@endphp

<div
    class=" bg-amber-700 p-4 rounded-lg shadow-md flex flex-col gap-2 w-full border-l-8 {{ $borderColor }} transition hover:shadow-md bg-white">
    <div class="flex gap-2 items-center">
        <x-icons.note-book />
        <p class="text-lg font-bold text-black">{{ $schedule->course->code ?? 'BICT1101' }}</p>
    </div>
    <div class="flex gap-2 items-center">
        <x-icons.classroom />
        <p class="text-lg font-bold text-black">{{ $schedule->venue->name ?? 'ICT LAB 1' }}</p>
    </div>
    <div class="flex gap-2 items-center">
        <x-icons.clock />
        <p class="text-lg font-bold text-black">{{ $schedule->start_time }} - {{ $schedule->end_time }}</p>
    </div>
</div>
