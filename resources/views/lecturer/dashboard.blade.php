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

 @extends('layouts.app')

 @section('content')
     <div class="mb-6">
         <h2 class="text-2xl font-bold text-gray-800">
             {{ $greeting }}, {{ Auth::user()->first_name }}!
         </h2>
         <p class="text-sm text-gray-500">{{ $formattedDate }}</p>
         <p class="text-sm text-gray-600 mt-1">Here are your schedules for today.</p>
     </div>

     <h3 class="text-lg font-semibold text-gray-800 mb-3">Today's Schedules</h3>

     @if (false)
         @foreach ($schedules as $schedule)
             <div class="bg-yellow-100 p-4 rounded-lg shadow-md mb-4">
                 <div class="flex items-center mb-1">
                     <i class="fas fa-book mr-2 text-yellow-700"></i>
                     <strong>{{ $schedule->course_code }} ({{ $schedule->course_name }})</strong>
                 </div>
                 <p class="text-gray-700">{{ $schedule->venue }}</p>
                 <p class="text-gray-700">{{ $schedule->start_time->format('H:i') }} -
                     {{ $schedule->end_time->format('H:i') }}</p>
             </div>
         @endforeach
     @else
         <div class="bg-white p-6 rounded-lg shadow-md mb-4">
             <p class="text-gray-600">You have no scheduled classes today.</p>
         </div>
     @endif

     {{-- FULL SCHEDULE LINK --}}
     <div class="bg-white p-4 rounded-lg shadow-md mt-6">
         <a href="{{ route('lecturer.weekly-timetable') }}" class="text-green-600 font-medium hover:underline">
             View Full Weekly Schedule →
         </a>
     </div>
 @endsection
