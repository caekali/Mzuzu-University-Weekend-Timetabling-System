   @php
       $days = [
           ['id' => 1, 'name' => 'Monday'],
           ['id' => 2, 'name' => 'Tuesday'],
           ['id' => 3, 'name' => 'Wednesday'],
           ['id' => 4, 'name' => 'Thursday'],
           ['id' => 5, 'name' => 'Friday'],
       ];

       $timeSlots = [
           ['id' => 1, 'startTime' => '08:00', 'endTime' => '09:00'],
           ['id' => 2, 'startTime' => '09:00', 'endTime' => '10:00'],
           ['id' => 3, 'startTime' => '10:00', 'endTime' => '11:00'],
           ['id' => 4, 'startTime' => '11:00', 'endTime' => '12:00'],
           ['id' => 5, 'startTime' => '12:00', 'endTime' => '13:00'],
           ['id' => 6, 'startTime' => '13:00', 'endTime' => '14:00'],
           ['id' => 7, 'startTime' => '14:00', 'endTime' => '15:00'],
           ['id' => 8, 'startTime' => '15:00', 'endTime' => '16:00'],
           ['id' => 9, 'startTime' => '16:00', 'endTime' => '17:00'],
       ];

   @endphp
   <div class="py-6">
       <div class="flex justify-between items-center mb-6 print:hidden">
           <h1 class="text-2xl font-bold text-gray-900">Timetable</h1>
           <button
               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
               <x-lucide-filter class="h-4 w-4 mr-1" />
               Filters
           </button>
       </div>

       <div class="bg-white rounded-lg shadow-sm p-2 md:p-6 overflow-x-auto">
           <h2 class="text-lg font-medium text-gray-900 mb-4 px-2 ">
               Weekly Schedule
           </h2>
           <div class="min-w-full overflow-hidden print:min-w-0">
               <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                   <thead>
                       <tr>
                           <th
                               class="px-2 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                               Time
                           </th>
                           @foreach ($days as $day)
                               <th
                                   class="px-2 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                   {{ $day['name'] }}
                               </th>
                           @endforeach
                       </tr>
                   </thead>
                   <tbody class="bg-white divide-y divide-gray-200">
                       @foreach ($timeSlots as $timeSlot)
                           <tr class="divide-x divide-gray-200">
                               <td
                                   class="px-2 py-1 md:py-2 whitespace-nowrap text-xs md:text-sm text-gray-500 bg-gray-50 font-medium">
                                   {{ $timeSlot['startTime'] }} - <br> {{ $timeSlot['endTime'] }}
                               </td>
                               @foreach ($days as $day)
                                   <td class="px-1 md:px-2 py-1 md:py-2 align-top cursor-pointer hover:bg-gray-50">
                                       <div class="h-16 w-full flex items-center justify-center text-gray-400 hover:text-gray-600"
                                           wire:click="openModal({{ 0 }},'{{ $day['name'] }}','{{ $timeSlot['startTime'] }}','{{ $timeSlot['endTime'] }}')">
                                           <x-lucide-plus class="h-4 w-4" />
                                       </div>
                                   </td>
                               @endforeach
                           </tr>
                       @endforeach
                   </tbody>
               </table>
           </div>
       </div>
       <livewire:timetable.schedule-modal />
   </div>
