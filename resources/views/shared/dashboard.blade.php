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

 @extends('layouts.app')

 @section('content')
     @php
         $currentRole = session('current_role');
     @endphp

     @if ($currentRole === 'Admin')
         @include('partials.dashboard-admin')
     @elseif ($currentRole === 'Student')
         @include('partials.dashboard-student')
     @elseif ($currentRole === 'Lecturer')
         @include('partials.dashboard-lecturer')
      @elseif ($currentRole === 'HOD')
         @include('partials.dashboard-hod')
     @else
{{abort(401)}}     @endif
     
 @endsection
