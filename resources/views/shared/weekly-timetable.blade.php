@extends('layouts.app')

@php
    $schedules = collect([
        (object) [
            'day' => 'Mon',
            'course' => (object) ['code' => 'BICT1101'],
            'venue' => (object) ['name' => 'ICT LAB 1'],
            'start_time' => '07:45',
            'end_time' => '09:45',
        ],
        (object) [
            'day' => 'Tue',
            'course' => (object) ['code' => 'BICT1202'],
            'venue' => (object) ['name' => 'Lecture Room 2'],
            'start_time' => '10:00',
            'end_time' => '12:00',
        ],
        (object) [
            'day' => 'Wed',
            'course' => (object) ['code' => 'MATH1103'],
            'venue' => (object) ['name' => 'Main Hall'],
            'start_time' => '13:00',
            'end_time' => '15:00',
        ],
        // Add more mock entries as needed
    ]);
@endphp

@section('content')
    <p class="text-2xl font-bold text-gray-800 mb-6">Weekly Schedules</p>
    <div x-data="{ selectedDay: '{{ $selectedDay ?? 'Mon' }}' }" class="space-y-4">

        <!-- Day Tabs -->
        <ul class="flex space-x-4 mb-4">
            @foreach (['Mon', 'Tue', 'Wed', 'Thurs', 'Fri', 'Sat', 'Sun'] as $day)
                <li>
                    <button @click="selectedDay = '{{ $day }}'"
                        class="px-4 py-2 rounded font-medium text-sm transition"
                        :class="selectedDay === '{{ $day }}' ? 'bg-green-600 text-white' :
                            'bg-gray-200 hover:bg-green-100'">
                        {{ $day }}
                    </button>
                </li>
            @endforeach
        </ul>

        <!-- Schedules -->
        <div class="space-y-4">
            @foreach ($schedules as $schedule)
                <div x-show="selectedDay === '{{ $schedule->day }}'" x-transition>
                    <x-timetable.card :schedule="$schedule" />
                </div>
            @endforeach
        </div>
    </div>
@endsection
