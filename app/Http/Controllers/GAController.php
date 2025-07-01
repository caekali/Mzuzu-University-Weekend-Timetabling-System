<?php

namespace App\Http\Controllers;

use App\DTO\CourseDTO;
use App\Models\LecturerCourseAllocation;
use App\Services\GeneticAlgorithm\GeneticAlgorithm;

class GAController extends Controller
{

  public function generate()
  {
    $data = new Data();

    $ga = new GeneticAlgorithm(
      populationSize: 10,
      eliteSchedules: 1,
      crossoverRate: 0.8,
      mutationRate: 0.05,
      tournamentSize: 3,
      data: $data->getData()
    );

    $population = $ga->initializePopulation();

    $bestSchedule = $population->getFittest();

    for ($i = 0; $bestSchedule->getFitness() != 1.0 && $i < 100; $i++) {
      $population = $ga->evolve($population);
      $bestSchedule = $population->getFittest();
    }


    return response()->json([
      'fitness' => $bestSchedule->getFitness(),
      'No. Hard Of Conflicts' => $bestSchedule->getNumberOfHardConflicts(),
      'No. Soft Of Conflicts' => $bestSchedule->getNumberOfSoftConflicts(),
      'schedule' => array_map(function ($entry) {
        return [
          'course' => $entry->course->code,
          'lecturer' => $entry->lecturer,
          'venue' => $entry->venue->name,
          'time' => $entry->timeSlots,
        ];
      }, $bestSchedule->getScheduleEntries()),
    ]);
  }
}

class Data
{

  private $courses = [];
  private $venues = [];
  private $timeslots = [];

  public function __construct()
  {
    $this->venues = [
      (object)['id' => 1, 'name' => 'Room A', 'capacity' => 100],
      (object)['id' => 2, 'name' => 'Room B', 'capacity' => 70],
      (object)['id' => 3, 'name' => 'Room C', 'capacity' => 80],
      (object)['id' => 4, 'name' => 'Room D', 'capacity' => 60],
      (object)['id' => 5, 'name' => 'Room E', 'capacity' => 90],
    ];

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

    $this->courses = [
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

    $levels = [1, 2, 3, 4];


    foreach ($this->courses as $course) {
      $course->level = $levels[array_rand($levels)];
      $course->semester = 1;
      // $course->weekly_hours = 4;
    }
    return $this->timeslots = $this->generate();
  }

  public  function generate(array $days = ['Friday', 'Saturday', 'Sunday'], string $start = '07:45', string $end = '18:45', int $slotMinutes = 60): array
  {
    $slots = [];
    foreach ($days as $day) {
      $current = strtotime($start);
      $endTime = strtotime($end);

      while ($current < $endTime) {
        $next = strtotime("+$slotMinutes minutes", $current);

        $slots[] = [
          'day' => $day,
          'start' => date('H:i', $current),
          'end' => date('H:i', $next),
        ];

        $current = $next;
      }
    }
    return $slots;
  }
  protected function getCourses(): array
  {
    $allocations = LecturerCourseAllocation::with([
      'lecturer',
      'course',
      'programmes'
    ])->get();

    $courseData = $allocations->map(function ($allocation) {
      $programmes = $allocation->programmes->map(function ($programme) use ($allocation) {
        return $programme->code . $allocation->course->level . $allocation->course->semester;
      })->toArray();

      return new CourseDTO(
        $allocation->course->id,
        $allocation->course->code,
        $allocation->course->weekly_hours,
        $allocation->course->num_of_students,
        $allocation->lecturer->id,
        $programmes
      );
    })->toArray();
    return $courseData;
  }

  public function getVenues()
  {
    return $this->venues;
  }

  public function getTimeslots()
  {
    return $this->timeslots;
  }

  public function getData()
  {
    return [
      'courses' => $this->getCourses(),
      'venues' => $this->getVenues(),
      'timeslots' => $this->getTimeslots()
    ];
  }

  
}
