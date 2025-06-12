<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Venue;
use App\Services\Scheduling\GeneticAlgorithm;
use Illuminate\Http\Request;

class GAController extends Controller
{
  public function run()
  {
    $scheduler = new GeneticAlgorithm();
    $bestSchedule = $scheduler->run();

    return response()->json($bestSchedule);
    return view('dashboard', ['best' => $bestSchedule]);
  }


  public function generateSchedule()
  {
    // Dummy Programmes
    $programmes = [
      (object)['id' => 1, 'name' => 'Computer Science'],
      (object)['id' => 2, 'name' => 'Information Systems'],
      (object)['id' => 3, 'name' => 'Software Engineering'],
    ];

    // Dummy Lecturers
    $lecturers = [
      (object)['id' => 1, 'name' => 'Dr. Smith'],
      (object)['id' => 2, 'name' => 'Prof. Jane'],
      (object)['id' => 3, 'name' => 'Mr. Alex'],
      (object)['id' => 4, 'name' => 'Dr. Grace'],
    ];

    // Dummy Courses (6 courses)
    $courses = [
      (object)[
        'id' => 1,
        'name' => 'Algorithms',
        'expected_students' => 80,
        'lecturer_id' => 1,
        'lecturer' => $lecturers[0],
        'programmes' => [$programmes[0]],
      ],
      (object)[
        'id' => 2,
        'name' => 'Database Systems',
        'expected_students' => 60,
        'lecturer_id' => 2,
        'lecturer' => $lecturers[1],
        'programmes' => [$programmes[0], $programmes[1]],
      ],
      (object)[
        'id' => 3,
        'name' => 'Operating Systems',
        'expected_students' => 70,
        'lecturer_id' => 1,
        'lecturer' => $lecturers[0],
        'programmes' => [$programmes[1]],
      ],
      (object)[
        'id' => 4,
        'name' => 'Software Engineering',
        'expected_students' => 75,
        'lecturer_id' => 3,
        'lecturer' => $lecturers[2],
        'programmes' => [$programmes[2]],
      ],
      (object)[
        'id' => 5,
        'name' => 'Networks',
        'expected_students' => 65,
        'lecturer_id' => 4,
        'lecturer' => $lecturers[3],
        'programmes' => [$programmes[0], $programmes[2]],
      ],
      (object)[
        'id' => 6,
        'name' => 'Cybersecurity',
        'expected_students' => 55,
        'lecturer_id' => 3,
        'lecturer' => $lecturers[2],
        'programmes' => [$programmes[1], $programmes[2]],
      ],
    ];

    // Dummy Venues
    $venues = [
      (object)['id' => 1, 'name' => 'Lecture Hall A', 'capacity' => 100],
      (object)['id' => 2, 'name' => 'Lecture Hall B', 'capacity' => 70],
      (object)['id' => 3, 'name' => 'Lecture Hall C', 'capacity' => 80],
    ];

    // Dummy TimeSlots (5 days × 4 slots = 20 total)
    $timeSlots = [];
    foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day) {
      foreach ([['08:00', '10:00'], ['10:00', '12:00'], ['13:00', '15:00'], ['15:00', '17:00']] as [$start, $end]) {
        $timeSlots[] = ['day' => $day, 'start' => $start, 'end' => $end];
      }
    }

    // Run Genetic Algorithm
    $ga = new \App\Services\GeneticAlgorithm\GeneticAlgorithm($courses, $venues, $timeSlots);
    $best = $ga->run();

    return response()->json([
      'fitness' => $best->fitness,
      'schedule' => array_map(function ($entry) {
        return [
          'course' => $entry->course->name,
          'lecturer' => $entry->course->lecturer->name,
          'venue' => $entry->venue->name,
          'time' => $entry->timeSlot,
        ];
      }, $best->entries),
    ]);
  }
}
