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


 @php
     $schedules = collect([
         (object) [
             'day' => 'Tue',
             'course' => (object) ['code' => 'BICT1202'],
             'venue' => (object) ['name' => 'ICT LAB 2'],
             'start_time' => '10:00',
             'end_time' => '12:00',
         ],
         (object) [
             'day' => 'Wed',
             'course' => (object) ['code' => 'MATH1203'],
             'venue' => (object) ['name' => 'ICT LAB 1'],
             'start_time' => '13:00',
             'end_time' => '15:00',
         ],
         // Add more mock entries as needed
     ]);
 @endphp

 <div class="flex flex-col h-full">
     <div class="mb-6">
         <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
             {{ $greeting }}, {{ Auth::user()->first_name }}!
         </h2>
         <p class="text-sm text-gray-500 dark:text-gray-200">{{ $formattedDate }}</p>
         <p class="text-sm text-gray-600 dark:text-gray-200 mt-1">Here are your schedules for today.</p>
     </div>

     <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Today's Schedules</h3>

     @if ($schedules && count($schedules) > 0)
         <!-- Scrollable container takes remaining height -->
         <div class="flex-1 overflow-y-auto space-y-4">
             @foreach ($schedules as $schedule)
                 <x-timetable.card :schedule="$schedule" />
             @endforeach
         </div>
     @else
         <div class="bg-white dark:bg-slate-900 p-6 rounded-lg shadow-md mb-4">
             <p class="text-gray-600">You have no scheduled classes today.</p>
         </div>
     @endif

     <div class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-md mt-6">
         <a href="{{ route('timetable') }}" class="text-green-600 font-medium hover:underline">
             View Full Weekly Schedule →
         </a>
     </div>
 </div>
