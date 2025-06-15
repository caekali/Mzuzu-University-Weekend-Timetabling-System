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
 <div>
     @if ($currentRole == 'Admin')
         @livewire('dashboard.admin-panel')
     @else
         @livewire('dashboard.student-or-lecturer-panel')
     @endif
 </div>
