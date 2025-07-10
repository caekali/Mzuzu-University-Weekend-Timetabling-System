<?php

namespace App\Http\Controllers;

use App\Services\GeneticAlgorithm\GADataLoaderService;
use App\Services\GeneticAlgorithm\GeneticAlgorithm;
use App\Services\GeneticAlgorithm\ScheduleEntry;

class TestController extends Controller
{

  public function generate()
  {
    $data = app(GADataLoaderService::class)->loadGAData();
    $schedule = Schedule::generateRandomSchedule($data);

    for ($i = 0; $i < 1; $i++) {
      $schedule = Schedule::generateRandomSchedule($data);
      foreach ($schedule->getScheduleEntries() as $entry) {
        print_r($entry); // or format/display
      }
    }
  }
}


class Schedule
{
  protected array $scheduleEntries = [];

  public static function generateRandomSchedule(array $data): Schedule
  {
    $schedule = new Schedule();
    $courses = $data['courses'];
    $venues = $data['venues'];
    $timeSlots = $data['timeslots'];
    // dd($timeSlots);

    foreach ($courses as $course) {
      $sessions = self::parseLectureHours($course->lecture_hours);

      foreach ($sessions as $i => $hours) {
        $slotSet = self::findConsecutiveTimeSlots($timeSlots, $hours);

        if (!empty($slotSet)) {
          shuffle($venues);
          $venue = $venues[0];
          $key = "{$course->id}-$i";

          $schedule->scheduleEntries[$key] = new ScheduleEntry(
            $course,
            $course->lecturer_id,
            $venue,
            $slotSet,
            $course->level,
            $course->programmes
          );
        }
      }
    }

    return $schedule;
  }

  private static function parseLectureHours(string $lectureHours): array
  {
    return array_map('intval', explode(',', $lectureHours));
  }

private static function findConsecutiveTimeSlots(array $timeSlots, int $requiredHours): array
{
    $sessionSlots = collect($timeSlots)
        ->filter(fn($s) => ($s['type'] ?? 'session') === 'session')
        ->sortBy(['day', 'start'])
        ->values();

    $requiredMinutes = $requiredHours * 60;
    $n = $sessionSlots->count();
    $validSets = [];

    for ($i = 0; $i < $n; $i++) {
        $set = [];
        $totalMinutes = 0;

        for ($j = $i; $j < $n; $j++) {
            $curr = $sessionSlots[$j];

            if (!empty($set)) {
                $prev = end($set);
                if (
                    $prev['day'] !== $curr['day'] ||
                    strtotime($prev['end']) !== strtotime($curr['start'])
                ) {
                    break;
                }
            }

            $duration = (strtotime($curr['end']) - strtotime($curr['start'])) / 60;
            $totalMinutes += $duration;
            $set[] = $curr;

            if ($totalMinutes >= $requiredMinutes) {
                $validSets[] = $set;
                break; // Done with this sequence, go to next starting point
            }
        }
    }

    return !empty($validSets) ? $validSets[array_rand($validSets)] : [];
}


  public function getScheduleEntries(): array
  {
    return $this->scheduleEntries;
  }
}
