<div class="flex flex-col h-full">
         <div class="mb-6">
             <h2 class="text-2xl font-bold text-gray-800">
                 {{ $greeting }}, {{ Auth::user()->first_name }}!
             </h2>
             <p class="text-sm text-gray-500">{{ $formattedDate }}</p>
             <p class="text-sm text-gray-600 mt-1">Here are your schedules for today.</p>
         </div>

         <h3 class="text-lg font-semibold text-gray-800 mb-3">Today's Schedules</h3>

         @if ($schedules && count($schedules) > 0)
             <!-- Scrollable container takes remaining height -->
             <div class="flex-1 overflow-y-auto space-y-4">
                 @foreach ($schedules as $schedule)
                     <x-timetable.card :schedule="$schedule" />
                 @endforeach
             </div>
         @else
             <div class="bg-white p-6 rounded-lg shadow-md mb-4">
                 <p class="text-gray-600">You have no scheduled classes today.</p>
             </div>
         @endif

         <div class="bg-white p-4 rounded-lg shadow-md mt-6">
             <a href="{{ route('weekly-timetable') }}" class="text-green-600 font-medium hover:underline">
                 View Full Weekly Schedule →
             </a>
         </div>
     </div>