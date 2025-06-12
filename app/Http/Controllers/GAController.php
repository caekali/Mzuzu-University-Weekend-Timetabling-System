<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Venue;
use App\Services\GeneticAlgorithm\GeneticAlgorithm;
use App\Services\GeneticAlgorithm\TimeSlotGenerator;
use Illuminate\Http\Request;

class GAController extends Controller
{

  public function generateSchedule()
  {
    $programmes = [
      (object)['id' => 1, 'name' => 'Computer Science'],
      (object)['id' => 2, 'name' => 'Information Systems'],
      (object)['id' => 3, 'name' => 'Software Engineering'],
      (object)['id' => 4, 'name' => 'Data Science'],
      (object)['id' => 5, 'name' => 'Cybersecurity'],
    ];

    $lecturers = [
      (object)['id' => 1, 'name' => 'Dr. Smith'],
      (object)['id' => 2, 'name' => 'Prof. Jane'],
      (object)['id' => 3, 'name' => 'Mr. Alex'],
      (object)['id' => 4, 'name' => 'Dr. Grace'],
      (object)['id' => 5, 'name' => 'Ms. Helen'],
      (object)['id' => 6, 'name' => 'Dr. Robert'],
      (object)['id' => 7, 'name' => 'Prof. Emma'],
    ];

    $courses = [
      (object)[
        'id' => 1,
        'name' => 'Algorithms',
        'expected_students' => 80,
        'weekly_hours' => 3,
        'lecturer_id' => 1,
        'lecturer' => $lecturers[0],
        'programmes' => [$programmes[0]],
      ],
      (object)[
        'id' => 2,
        'name' => 'Database Systems',
        'expected_students' => 60,
        'weekly_hours' => 4,
        'lecturer_id' => 2,
        'lecturer' => $lecturers[1],
        'programmes' => [$programmes[0], $programmes[1]],
      ],
      (object)[
        'id' => 3,
        'name' => 'Operating Systems',
        'expected_students' => 70,
        'weekly_hours' => 3,
        'lecturer_id' => 1,
        'lecturer' => $lecturers[0],
        'programmes' => [$programmes[1]],
      ],
      (object)[
        'id' => 4,
        'name' => 'Software Engineering',
        'expected_students' => 75,
        'weekly_hours' => 2,
        'lecturer_id' => 3,
        'lecturer' => $lecturers[2],
        'programmes' => [$programmes[2]],
      ],
      (object)[
        'id' => 5,
        'name' => 'Networks',
        'expected_students' => 65,
        'weekly_hours' => 2,
        'lecturer_id' => 4,
        'lecturer' => $lecturers[3],
        'programmes' => [$programmes[0], $programmes[2]],
      ],
      (object)[
        'id' => 6,
        'name' => 'Cybersecurity',
        'expected_students' => 55,
        'weekly_hours' => 4,
        'lecturer_id' => 3,
        'lecturer' => $lecturers[2],
        'programmes' => [$programmes[1], $programmes[2]],
      ],
      (object)[
        'id' => 7,
        'name' => 'Machine Learning',
        'expected_students' => 50,
        'weekly_hours' => 2,
        'lecturer_id' => 5,
        'lecturer' => $lecturers[4],
        'programmes' => [$programmes[3]],
      ],
      (object)[
        'id' => 8,
        'name' => 'Data Mining',
        'expected_students' => 45,
        'weekly_hours' => 2,
        'lecturer_id' => 6,
        'lecturer' => $lecturers[5],
        'programmes' => [$programmes[3]],
      ],
      (object)[
        'id' => 9,
        'name' => 'Cryptography',
        'expected_students' => 40,
        'weekly_hours' => 2,
        'lecturer_id' => 7,
        'lecturer' => $lecturers[6],
        'programmes' => [$programmes[4]],
      ],
      (object)[
        'id' => 10,
        'name' => 'Network Security',
        'expected_students' => 55,
        'weekly_hours' => 2,
        'lecturer_id' => 4,
        'lecturer' => $lecturers[3],
        'programmes' => [$programmes[4]],
      ],
      (object)[
        'id' => 11,
        'name' => 'Big Data Analytics',
        'expected_students' => 60,
        'weekly_hours' => 3,
        'lecturer_id' => 6,
        'lecturer' => $lecturers[5],
        'programmes' => [$programmes[3]],
      ],
      (object)[
        'id' => 12,
        'name' => 'Advanced Software Engineering',
        'expected_students' => 50,
        'weekly_hours' => 2,
        'lecturer_id' => 2,
        'lecturer' => $lecturers[1],
        'programmes' => [$programmes[2]],
      ],
    ];

    $venues = [
      (object)['id' => 1, 'name' => 'Room A', 'capacity' => 100],
      (object)['id' => 2, 'name' => 'Room B', 'capacity' => 70],
      (object)['id' => 3, 'name' => 'Room C', 'capacity' => 80],
      (object)['id' => 4, 'name' => 'Room D', 'capacity' => 60],
      (object)['id' => 5, 'name' => 'Room E', 'capacity' => 90],
    ];

    $timeSlots = [];
    foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day) {
      foreach ([['08:00', '09:00'], ['10:00', '11:00'], ['12:00', '13:00'], ['14:00', '15:00']] as [$start, $end]) {
        $timeSlots[] = ['day' => $day, 'start' => $start, 'end' => $end];
      }
    }

    $timeSlots = TimeSlotGenerator::generate();

    // Run Genetic Algorithm
    $ga = new GeneticAlgorithm($courses, $venues, $timeSlots);
    $best = $ga->run();

    return response()->json([
      'fitness' => $best->fitness,
      'No. Of Conflicts' => $best->numOfConflicts,
      'schedule' => array_map(function ($entry) {
        return [
          'course' => $entry->course->name,
          'lecturer' => $entry->lecturer->name,
          'programme' => $entry->course->programmes,
          'venue' => $entry->venue->name,
          'time' => $entry->timeSlots,
        ];
      }, $best->entries),
    ]);
  }
}
