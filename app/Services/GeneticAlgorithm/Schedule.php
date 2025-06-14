<?php

namespace App\Services\GeneticAlgorithm;

use Illuminate\Support\Facades\Log;

class Schedule
{
    public array $entries = [];
    public float $fitness = 0;
    public int $numOfConflicts = 0;

    public function __construct(array $entries = [])
    {
        $this->entries = $entries;
    }

    public static function generateRandomSchedule(array $courses, array $venues, array $timeSlots): Schedule
    {
        $schedule = new Schedule();

        foreach ($courses as $course) {
            $sessions = self::splitTeachingHours($course->weekly_hours);
            foreach ($sessions as $hours) {
                $slotSet = static::randomConsecutiveTimeSlots($timeSlots, $hours);
                $venue = $venues[array_rand($venues)];
               $schedule->entries[] = new ScheduleEntry($course, $course->lecturer_id, $venue, $slotSet);
            }
        }

        $schedule->calculateFitness();
        return $schedule;
    }

    private static function splitTeachingHours(int $hours): array
    {
        $first = rand(0, 1) ? 1 : 2;
        $first = min($first, $hours);
        $remaining = $hours - $first;
        $parts = [$first];
        if ($remaining > 0) {
            $parts[] = $remaining;
        }
        return $parts;
    }

    private static function randomConsecutiveTimeSlots(array $timeSlots, int $length): array
    {
        $max = count($timeSlots) - $length;
        $start = rand(0, $max);
        return array_slice($timeSlots, $start, $length);
    }


    public function calculateFitness(): void
    {
        $score = 0;
        $conflicts = 0;

        foreach ($this->entries as $i => $entry1) {
            foreach ($this->entries as $j => $entry2) {
                if ($i >= $j) continue;

                // TimeSlot overlap
                if ($this->overlaps($entry1->timeSlots, $entry2->timeSlots)) {
                    // Lecturer conflict
                    if ($entry1->course->lecturer_id === $entry2->course->lecturer_id) {
                        $conflicts++;
                    }

                    // // Programme conflict
                    // $prog1 = collect($entry1->course->programmes)->pluck('id')->all();
                    // $prog2 = collect($entry2->course->programmes)->pluck('id')->all();
                    // if (count(array_intersect($prog1, $prog2)) > 0) {
                    //     $conflicts++;
                    // }

                    // Venue conflict
                    if ($entry1->venue->id === $entry2->venue->id) {
                        $conflicts++;
                    }
                }
            }

            // Room capacity check
            if ($entry1->course->expected_students > $entry1->venue->capacity) {
                $conflicts++;
            }
        }

        $this->numOfConflicts = $conflicts;
        $this->fitness = 1 / (1 + $conflicts);
    }

    private function overlaps(array $slots1, array $slots2): bool
    {
        foreach ($slots1 as $s1) {
            foreach ($slots2 as $s2) {
                if ($s1['day'] === $s2['day'] && $s1['start'] === $s2['start']) {
                    return true;
                }
            }
        }
        return false;
    }

    public function mutate(array $venues, array $timeSlots): void
    {
        $i = array_rand($this->entries);
        if (rand(0, 1)) {
            $length = count($this->entries[$i]->timeSlots);
            $this->entries[$i]->timeSlots = self::randomConsecutiveTimeSlots($timeSlots, $length);
        } else {
            $this->entries[$i]->venue = $venues[array_rand($venues)];
        }

        $this->calculateFitness();
    }

    public function crossover(Schedule $partner): array
    {
        $groupA = $this->groupEntriesByCourse($this->entries);
        $groupB = $this->groupEntriesByCourse($partner->entries);

        $courses = array_keys($groupA);
        sort($courses);

        $split = floor(count($courses) / 2);

        $child1Entries = [];
        $child2Entries = [];

        for ($i = 0; $i < $split; $i++) {
            $course = $courses[$i];
            $child1Entries = array_merge($child1Entries, $groupA[$course]);
            $child2Entries = array_merge($child2Entries, $groupB[$course]);
        }

        for ($i = $split; $i < count($courses); $i++) {
            $course = $courses[$i];
            $child1Entries = array_merge($child1Entries, $groupB[$course]);
            $child2Entries = array_merge($child2Entries, $groupA[$course]);
        }

        $child1 = new Schedule($child1Entries);
        $child2 = new Schedule($child2Entries);

        $child1->calculateFitness();
        $child2->calculateFitness();

        return [$child1, $child2];
    }

    protected function limitSessionsPerCourseByName(array $entries, array $courses)
    {
        $grouped = [];

        foreach ($entries as $entry) {
            $courseName = $entry->course ?? $entry['course'];
            $grouped[$courseName][] = $entry;
        }

        $limited = [];

        foreach ($grouped as $courseName => $courseEntries) {
            // Find matching course by name
            $course = collect($courses)->firstWhere('name', $courseName);

            $weeklyHours = $course->weekly_hours ?? count($courseEntries);

            // Trim to allowed weekly hours
            if (count($courseEntries) > $weeklyHours) {
                shuffle($courseEntries);
                $courseEntries = array_slice($courseEntries, 0, $weeklyHours);
            }

            $limited = array_merge($limited, $courseEntries);
        }

        return $limited;
    }

    function groupEntriesByCourse(array $entries): array
    {
        $grouped = [];
        foreach ($entries as $entry) {
            $courseName = $entry->course->name;
            if (!isset($grouped[$courseName])) {
                $grouped[$courseName] = [];
            }
            $grouped[$courseName][] = $entry;
        }
        return $grouped;
    }

    // protected function groupEntriesByCourse()
    // {
    //     $grouped = [];

    //     foreach ($this->entries as $entry) {
    //         $courseName = $entry->course->name ?? $entry['course']['name'] ?? null;
    //         if ($courseName) {
    //             $grouped[$courseName][] = $entry;
    //         }
    //     }

    //     return $grouped;
    // }

    protected function deepCloneEntries(array $entries)
    {
        return array_map(function ($entry) {
            return clone $entry;
        }, $entries);
    }


    //  public function isSlotTaken(int $resourceId, int $timeSlot, string $resourceType = 'venue'): bool
    // {
    //     foreach ($this->entries as $entry) {
    //         if ($resourceType === 'venue' && $entry['venue_id'] === $resourceId && $entry['time_slot'] === $timeSlot) {
    //             return true;
    //         }

    //         if ($resourceType === 'lecturer' && $entry['lecturer_id'] === $resourceId && $entry['time_slot'] === $timeSlot) {
    //             return true;
    //         }
    //     }
    //     return false;
    // }


    //  public function mutate(float $mutationRate, array $venues): void
    // {
    //     foreach ($this->entries as &$entry) {
    //         if (mt_rand() / mt_getrandmax() < $mutationRate) {
    //             $entry['venue_id'] = $venues[array_rand($venues)]['id'];
    //             $entry['time_slots'][0]['day'] = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'][array_rand([0, 1, 2, 3, 4])];
    //             $entry['time_slots'][0]['start_time'] = sprintf('%02d:00:00', rand(8, 16));
    //         }
    //     }
    // }





    //  public static function generateRandom(array $courses, array $venues): self
    // {
    //     $schedule = new self();

    //     foreach ($courses as $course) {
    //         $venue = $venues[array_rand($venues)];
    //         $lecturerId = $course['allocations'][0]['lecturer_id'] ?? null;
    //         $programmeId = $course['programmes'][0]['id'] ?? null;

    //         $schedule->entries[] = [
    //             'course_id' => $course['id'],
    //             'lecturer_id' => $lecturerId,
    //             'venue_id' => $venue['id'],
    //             'programme_id' => $programmeId,
    //             'time_slots' => [
    //                 [
    //                     'day' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'][array_rand([0, 1, 2, 3, 4])],
    //                     'start_time' => sprintf('%02d:00:00', rand(8, 16))
    //                 ]
    //             ]
    //         ];
    //     }

    //     return $schedule;
    // }

    protected function timeSlotsOverlap(array $slots1, array $slots2): bool
    {
        foreach ($slots1 as $slot1) {
            foreach ($slots2 as $slot2) {
                if (
                    $slot1['day'] === $slot2['day'] &&
                    (
                        (strtotime($slot1['start_time']) < strtotime($slot2['end_time'])) &&
                        (strtotime($slot2['start_time']) < strtotime($slot1['end_time']))
                    )
                ) {
                    return true;
                }
            }
        }
        return false;
    }


    protected function hasConflict(array $entries, int $lecturerId, int $venueId, array $timeSlots, int $programmeId): bool
    {
        foreach ($entries as $entry) {
            // Check lecturer clash
            if ($entry['lecturer_id'] === $lecturerId) {
                if ($this->timeSlotsOverlap($entry['time_slots'], $timeSlots)) {
                    return true;
                }
            }

            // Check venue clash
            if ($entry['venue_id'] === $venueId) {
                if ($this->timeSlotsOverlap($entry['time_slots'], $timeSlots)) {
                    return true;
                }
            }

            // Check programme clash (students can't attend 2 courses simultaneously)
            if ($entry['programme_id'] === $programmeId) {
                if ($this->timeSlotsOverlap($entry['time_slots'], $timeSlots)) {
                    return true;
                }
            }
        }

        return false; // no conflict detected
    }
}
