<?php

namespace App\Http\Controllers;

use App\DTO\CourseDTO;
use App\Models\LecturerCourseAllocation;
use App\Services\GeneticAlgorithm\GeneticAlgorithm;
use App\Services\GeneticAlgorithm\Population;

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

    dd($bestSchedule);
    return response()->json($bestSchedule);

    // return response()->json([
    //   'fitness' => $bestSchedule->getFitness(),
    //   'No. Of Conflicts' => $bestSchedule->getNumOfConflicts(),
    //   'schedule' => array_map(function ($entry) {
    //     return [
    //       'course' => $entry->course->name,
    //       'lecturer' => $entry->lecturer,
    //       'venue' => $entry->venue->name,
    //       'time' => $entry->timeSlots,
    //     ];
    //   }, $bestSchedule->getScheduleEntries()),
    // ]);
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
    // $courseData = $allocations->map(fn($allocation) => new CourseDTO(
    //     $allocation->course->id,
    //     $allocation->course->code,
    //     $allocation->course->weekly_hours,
    //     $allocation->course->num_of_students,
    //     $allocation->lecturer->id,
    //     $allocation->programmes->pluck('id')->toArray()
    // ))->toArray();
    // return $courseData;

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


// class ScheduleEntry
// {
//   public $course;
//   public $venue;
//   public $timeSlots = [];
//   public $lecturer;
//   public $level;

//   public function __construct($course, $lecturer, $venue, $timeslots)
//   {
//     $this->course = $course;
//     $this->lecturer = $lecturer;
//     $this->venue = $venue;
//     $this->timeSlots = $timeslots;
//     $this->level = $this->generateLevel($course);
//   }

//   private function generateLevel($course)
//   {
//     // Assuming course has programmes and semester attributes
//     $programmeIds = collect($course->programmes)->pluck('id')->all();
//     $programmePart = implode('-', $programmeIds);
//     return $programmePart . '-' . $course->level . '-' . $course->semester;
//   }

//   public function getLevel()
//   {
//     return $this->level;
//   }
// }

// class Schedule
// {
//   protected array $scheduleEntries = [];
//   private $numberOfConflicts = 0;
//   private $fitness = -1;
//   private $isFitnessChanged = true;

//   public static function generateRandomSchedule($data): Schedule
//   {
//     $schedule = new Schedule();
//     $courses = $data['courses'];
//     $venues = $data['venues'];
//     $timeSlots = $data['timeslots'];

//     $courseSessionMap = [];

//     foreach ($courses as $course) {
//       $courseSessionMap[$course->id] = self::splitTeachingHours($course->weekly_hours);
//       $sessions =    $courseSessionMap[$course->id];
//       foreach ($sessions as $i => $hours) {
//         $slotSet = static::randomConsecutiveTimeSlots($timeSlots, $hours);
//         $venue = $venues[array_rand($venues)];
//         $key = "{$course->id}-$i";
//         $schedule->scheduleEntries[$key] = new ScheduleEntry($course, $course->lecturer, $venue, $slotSet);
//       }
//     }

//     return $schedule;
//   }

//   private static function splitTeachingHours(int $hours): array
//   {
//     $first = rand(1, min(2, $hours));
//     $remaining = $hours - $first;
//     $parts = [$first];
//     if ($remaining > 0) {
//       $parts[] = $remaining;
//     }
//     return $parts;
//   }

//   private static function randomConsecutiveTimeSlots(array $timeSlots, int $length): array
//   {
//     $max = max(0, count($timeSlots) - $length);
//     $start = rand(0, $max);
//     return array_slice($timeSlots, $start, $length);
//   }

//   public function getFitness()
//   {
//     if ($this->isFitnessChanged) {
//       $this->fitness = $this->calculateFitness();
//       $this->isFitnessChanged = false;
//     }
//     return $this->fitness;
//   }

//   public function getNumOfConflicts()
//   {
//     return $this->numberOfConflicts;
//   }

//   private function overlaps(array $slots1, array $slots2): bool
//   {
//     foreach ($slots1 as $s1) {
//       foreach ($slots2 as $s2) {
//         if ($s1['day'] === $s2['day'] && $s1['start'] === $s2['start']) {
//           return true;
//         }
//       }
//     }
//     return false;
//   }

//   private function calculateFitness()
//   {
//     $conflicts = 0;

//     foreach ($this->scheduleEntries as $i => $entry1) {
//       foreach ($this->scheduleEntries as $j => $entry2) {
//         if ($i >= $j) continue;

//         if ($this->overlaps($entry1->timeSlots, $entry2->timeSlots)) {
//           if ($entry1->course->lecturer_id === $entry2->course->lecturer_id) {
//             $conflicts++;
//           }

//           $prog1 = collect($entry1->course->programmes)->pluck('id')->all();
//           $prog2 = collect($entry2->course->programmes)->pluck('id')->all();
//           if (count(array_intersect($prog1, $prog2)) > 0) {
//             $conflicts++;
//           }

//           if ($entry1->venue->id === $entry2->venue->id) {
//             $conflicts++;
//           }
//         }
//       }

//       if ($entry1->venue->capacity < $entry1->course->expected_students) {
//         $conflicts++;
//       }
//     }

//     $this->numberOfConflicts = $conflicts;
//     return 1 / ($conflicts + 1);
//   }

//   public function getScheduleEntries()
//   {
//     $this->isFitnessChanged = true;
//     return $this->scheduleEntries;
//   }

//   public function setScheduleEntries(array $scheduleEntries): void
//   {
//     $this->scheduleEntries = $scheduleEntries;
//     $this->isFitnessChanged = true;
//   }

//   public static function generateTemplateEntries($data): array
//   {
//     $schedule = self::generateRandomSchedule($data);
//     return $schedule->getScheduleEntries();
//   }
// }

// class Population
// {
//   public $schedules = [];

//   public function __construct($size, $data)
//   {
//     for ($i = 0; $i < $size; $i++) {
//       $this->schedules[] = Schedule::generateRandomSchedule($data);
//     }
//   }
//   public function getSchedules(): array
//   {
//     return $this->schedules;
//   }

//   public function setSchedules(array $schedules): void
//   {
//     $this->schedules = $schedules;
//   }

//   public function getFittest(): Schedule
//   {
//     usort($this->schedules, fn($a, $b) => $b->getFitness() <=> $a->getFitness());
//     return $this->schedules[0];
//   }
// }

// class GeneticAlgorithm
// {
//   const POPULATION_SIZE = 9;
//   const ELITE_SCHEDULES = 1;
//   const TOURNAMENT_SELECTION_SIZE = 3;
//   const MUTATION_RATE = 0.05;

//   public function evolve($population, $data)
//   {
//     $crossover = $this->generateCrossoverPopulation($population, $data);
//     return $this->applyMutation($crossover, $data);
//   }

//   private function generateCrossoverPopulation($pop, $data)
//   {
//     $newPop = new Population(0, $data);

//     for ($i = 0; $i < self::ELITE_SCHEDULES; $i++) {
//       $newPop->schedules[] = clone $pop->schedules[$i];
//     }

//     while (count($newPop->schedules) < self::POPULATION_SIZE) {
//       $parent1 = $this->tournamentSelection($pop);
//       $parent2 = $this->tournamentSelection($pop);
//       $child = $this->crossoverSchedules($parent1, $parent2, $data);
//       $newPop->schedules[] = $child;
//     }

//     return $newPop;
//   }

//   private function applyMutation($population, $data)
//   {
//     for ($i = self::ELITE_SCHEDULES; $i < self::POPULATION_SIZE; $i++) {
//       $this->mutateSchedule($population->schedules[$i], $data);
//     }
//     return $population;
//   }

//   private function crossoverSchedules($s1, $s2, $data)
//   {
//     $entries1 = $s1->getScheduleEntries();
//     $entries2 = $s2->getScheduleEntries();
//     $newEntries = Schedule::generateTemplateEntries($data);

//     foreach ($newEntries as $key => $_) {
//       $newEntries[$key] = rand(0, 1)
//         ? ($entries1[$key] ?? $entries2[$key])
//         : ($entries2[$key] ?? $entries1[$key]);
//     }

//     $schedule = new Schedule();
//     $schedule->setScheduleEntries($newEntries);
//     return $schedule;
//   }

//   private function mutateSchedule($schedule, $data)
//   {
//     $original = $schedule->getScheduleEntries();
//     $mutated = Schedule::generateTemplateEntries($data);

//     foreach ($original as $key => $entry) {
//       if (mt_rand() / mt_getrandmax() < self::MUTATION_RATE && isset($mutated[$key])) {
//         $original[$key] = $mutated[$key];
//       }
//     }

//     $schedule->setScheduleEntries($original);
//   }

//   private function tournamentSelection($pop): Schedule
//   {
//     $contestants = [];
//     for ($i = 0; $i < self::TOURNAMENT_SELECTION_SIZE; $i++) {
//       $contestants[] = $pop->schedules[array_rand($pop->schedules)];
//     }

//     usort($contestants, fn($a, $b) => $b->getFitness() <=> $a->getFitness());
//     return $contestants[0];
//   }
// }
// class ScheduleEntry
// {
//   public $course;
//   public $venue;
//   public array $timeSlots = [];
//   public $lecturer;
//   public $level;

//   public function __construct($course, $lecturer, $venue, $timeslots)
//   {
//     $this->course = $course;
//     $this->lecturer = $lecturer;
//     $this->venue = $venue;
//     $this->timeSlots = $timeslots;
//     $this->level = $this->generateLevel($course);
//   }

//   private function generateLevel($course)
//   {
//     $programmeIds = collect($course->programmes)->pluck('id')->all();
//     $programmePart = implode('-', $programmeIds);
//     return $programmePart . '-' . $course->level . '-' . $course->semester;
//   }

//   public function getLevel()
//   {
//     return $this->level;
//   }
// }

// class Schedule
// {
//   protected array $scheduleEntries = [];
//   private $numberOfConflicts = 0;
//   private $fitness = -1;
//   private $isFitnessChanged = true;

//   public static function generateRandomSchedule($data): Schedule
//   {
//     $schedule = new Schedule();
//     $courses = $data['courses'];
//     $venues = $data['venues'];
//     $timeSlots = $data['timeslots'];

//     foreach ($courses as $course) {
//       $sessions = self::parseLectureHours($course->weekly_hours);

//       foreach ($sessions as $i => $hours) {
//         $slotSet = static::randomConsecutiveTimeSlots($timeSlots, $hours);
//         $venue = $venues[array_rand($venues)];
//         $key = "{$course->id}-$i";
//         $schedule->scheduleEntries[$key] = new ScheduleEntry($course, $course->lecturer, $venue, $slotSet);
//       }
//     }

//     return $schedule;
//   }

//   private static function parseLectureHours(string $lectureHours): array
//   {
//     return array_map('intval', explode(',', $lectureHours));
//   }

//   private static function randomConsecutiveTimeSlots(array $timeSlots, int $length): array
//   {
//     $max = max(0, count($timeSlots) - $length);
//     $attempts = 10;

//     while ($attempts-- > 0) {
//       $start = rand(0, $max);
//       $slice = array_slice($timeSlots, $start, $length);

//       if (self::areConsecutive($slice)) {
//         return $slice;
//       }
//     }

//     // fallback
//     return array_slice($timeSlots, 0, $length);
//   }

//   private static function areConsecutive(array $slots): bool
//   {
//     for ($i = 1; $i < count($slots); $i++) {
//       if ($slots[$i - 1]['day'] !== $slots[$i]['day']) return false;
//       if ((strtotime($slots[$i]['start']) - strtotime($slots[$i - 1]['start'])) !== 3600) return false;
//     }
//     return true;
//   }

//   public function getFitness()
//   {
//     if ($this->isFitnessChanged) {
//       $this->fitness = $this->calculateFitness();
//       $this->isFitnessChanged = false;
//     }
//     return $this->fitness;
//   }

//   public function getNumOfConflicts()
//   {
//     return $this->numberOfConflicts;
//   }

//   private function overlaps(array $slots1, array $slots2): bool
//   {
//     foreach ($slots1 as $s1) {
//       foreach ($slots2 as $s2) {
//         if ($s1['day'] === $s2['day'] && $s1['start'] === $s2['start']) {
//           return true;
//         }
//       }
//     }
//     return false;
//   }

//   private function calculateFitness()
//   {
//     $conflicts = 0;

//     foreach ($this->scheduleEntries as $i => $entry1) {
//       foreach ($this->scheduleEntries as $j => $entry2) {
//         if ($i >= $j) continue;

//         if ($this->overlaps($entry1->timeSlots, $entry2->timeSlots)) {
//           if ($entry1->course->lecturer_id === $entry2->course->lecturer_id) {
//             $conflicts++;
//           }

//           $prog1 = collect($entry1->course->programmes)->pluck('id')->all();
//           $prog2 = collect($entry2->course->programmes)->pluck('id')->all();
//           if (count(array_intersect($prog1, $prog2)) > 0) {
//             $conflicts++;
//           }

//           if ($entry1->venue->id === $entry2->venue->id) {
//             $conflicts++;
//           }
//         }
//       }

//       if ($entry1->venue->capacity < $entry1->course->expected_students) {
//         $conflicts++;
//       }
//     }

//     $this->numberOfConflicts = $conflicts;
//     return 1 / ($conflicts + 1);
//   }

//   public function getScheduleEntries()
//   {
//     $this->isFitnessChanged = true;
//     return $this->scheduleEntries;
//   }

//   public function setScheduleEntries(array $scheduleEntries): void
//   {
//     $this->scheduleEntries = $scheduleEntries;
//     $this->isFitnessChanged = true;
//   }
// }

// class Population
// {
//   public array $schedules = [];

//   public function __construct($size, $data)


//   {
//     for ($i = 0; $i < $size; $i++) {
//       $this->schedules[] = Schedule::generateRandomSchedule($data);
//     }
//   }


//   // protected array $schedules;

//   //   public function __construct(array $schedules)
//   //   {
//   //       $this->schedules = $schedules;
//   //   }

//   public function getSchedules(): array
//   {
//     return $this->schedules;
//   }

//   public function setSchedules(array $schedules): void
//   {
//     $this->schedules = $schedules;
//   }

//   public function getFittest(): Schedule
//   {
//     usort($this->schedules, fn($a, $b) => $b->fitness <=> $a->fitness);
//     return $this->schedules[0];
//   }
// }

// class GeneticAlgorithm
// {
//   const POPULATION_SIZE = 9;
//   const ELITE_SCHEDULES = 1;
//   const TOURNAMENT_SELECTION_SIZE = 3;
//   const MUTATION_RATE = 0.05;

//   public function evolve($population, $data)
//   {
//     $crossover = $this->generateCrossoverPopulation($population, $data);
//     return $this->applyMutation($crossover, $data);
//   }

//   private function generateCrossoverPopulation($pop, $data)
//   {
//     $newPop = new Population(0, $data);

//     for ($i = 0; $i < self::ELITE_SCHEDULES; $i++) {
//       $newPop->schedules[] = clone $pop->schedules[$i];
//     }

//     while (count($newPop->schedules) < self::POPULATION_SIZE) {
//       $parent1 = $this->tournamentSelection($pop);
//       $parent2 = $this->tournamentSelection($pop);
//       $child = $this->crossoverSchedules($parent1, $parent2, $data);
//       $newPop->schedules[] = $child;
//     }

//     return $newPop;
//   }

//   private function applyMutation($population, $data)
//   {
//     for ($i = self::ELITE_SCHEDULES; $i < self::POPULATION_SIZE; $i++) {
//       $this->mutateSchedule($population->schedules[$i], $data);
//     }
//     return $population;
//   }

//   private function crossoverSchedules($s1, $s2, $data)
//   {
//     $entries1 = $s1->getScheduleEntries();
//     $entries2 = $s2->getScheduleEntries();
//     $schedule = Schedule::generateRandomSchedule($data);
//     $newEntries = $schedule->getScheduleEntries();

//     foreach ($newEntries as $key => $_) {
//       $newEntries[$key] = rand(0, 1)
//         ? ($entries1[$key] ?? $entries2[$key])
//         : ($entries2[$key] ?? $entries1[$key]);
//     }

//     $schedule->setScheduleEntries($newEntries);
//     return $schedule;
//   }

//   private function mutateSchedule($schedule, $data)
//   {
//     $original = $schedule->getScheduleEntries();
//     $mutated = Schedule::generateRandomSchedule($data)->getScheduleEntries();

//     foreach ($original as $key => $entry) {
//       if (mt_rand() / mt_getrandmax() < self::MUTATION_RATE && isset($mutated[$key])) {
//         $original[$key] = $mutated[$key];
//       }
//     }

//     $schedule->setScheduleEntries($original);
//   }

//   private function tournamentSelection($pop): Schedule
//   {
//     $contestants = [];
//     for ($i = 0; $i < self::TOURNAMENT_SELECTION_SIZE; $i++) {
//       $contestants[] = $pop->schedules[array_rand($pop->schedules)];
//     }

//     usort($contestants, fn($a, $b) => $b->getFitness() <=> $a->getFitness());
//     return $contestants[0];
//   }
// }
