<?php

namespace App\Services\Scheduling;

class Population
{
    public array $individuals = [];
    protected array $courses;
    protected array $venues;
    protected array $lecturers;
    protected array $allocations;
    protected float $mutationRate;
    protected array $timeSlots;


    public function __construct(
        array $courses,
        array $venues,
        array $lecturers,
        array $allocations,
        int $size,
        float $mutationRate
    ) {
        $this->courses = $courses;
        $this->venues = $venues;
        $this->lecturers = $lecturers;
        $this->allocations = $allocations;
        $this->mutationRate = $mutationRate;
        $this->timeSlots = TimeSlotGenerator::generate();

        for ($i = 0; $i < $size; $i++) {
            $schedule = $this->generateRandomSchedule();
            $this->individuals[] = new Individual($schedule);
        }
    }


 protected function generateRandomSchedule(): Schedule
{
    $schedule = new Schedule();

    foreach ($this->allocations as $allocation) {
        $course = $allocation['course'];
        $lecturer = $allocation['lecturer'];
        $programme = $allocation['programme'];

        $totalHours = $course['weekly_hours'];

        $twoHourSessions = (int) floor($totalHours / 2);
        $oneHourSessions = $totalHours % 2;

        // Create an array representing the session lengths
        $sessions = array_merge(
            array_fill(0, $twoHourSessions, 2),
            array_fill(0, $oneHourSessions, 1)
        );

        // Shuffle the session durations so 1h and 2h sessions order is random
        shuffle($sessions);

        foreach ($sessions as $sessionLength) {
            $venue = $this->venues[array_rand($this->venues)];

            if ($sessionLength === 2) {
                // Find valid 2-hour slots with consecutive slots
                $validStartSlots = [];
                foreach ($this->timeSlots as $index => $slot) {
                    if (
                        isset($this->timeSlots[$index + 1]) &&
                        $slot['day'] === $this->timeSlots[$index + 1]['day'] &&
                        strtotime($this->timeSlots[$index + 1]['start_time']) === strtotime($slot['end_time'])
                    ) {
                        $validStartSlots[] = $index;
                    }
                }
                shuffle($validStartSlots);

                $assigned = false;
                foreach ($validStartSlots as $startIndex) {
                    $slot1 = $this->timeSlots[$startIndex];
                    $slot2 = $this->timeSlots[$startIndex + 1];

                    if ($this->hasConflict($schedule->entries, $lecturer['id'], $venue['id'], [$slot1, $slot2], $programme['id'])) {
                        continue;
                    }

                    $schedule->addEntry([
                        'course_id' => $course['id'],
                        'lecturer_id' => $lecturer['id'],
                        'venue_id' => $venue['id'],
                        'time_slots' => [$slot1, $slot2],
                        'programme_id' => $programme['id'],
                    ]);
                    $assigned = true;
                    break;
                }
                if (!$assigned) {
                    echo "⚠️ Could not schedule 2-hour session for course: {$course['name']}\n";
                }
            } else { // 1 hour session
                // Single 1-hour slot sessions
                $validSlots = $this->timeSlots;
                shuffle($validSlots);

                $assigned = false;
                foreach ($validSlots as $slot) {
                    if ($this->hasConflict($schedule->entries, $lecturer['id'], $venue['id'], [$slot], $programme['id'])) {
                        continue;
                    }

                    $schedule->addEntry([
                        'course_id' => $course['id'],
                        'lecturer_id' => $lecturer['id'],
                        'venue_id' => $venue['id'],
                        'time_slots' => [$slot],
                        'programme_id' => $programme['id'],
                    ]);
                    $assigned = true;
                    break;
                }
                if (!$assigned) {
                    echo "⚠️ Could not schedule 1-hour session for course: {$course['name']}\n";
                }
            }
        }
    }

    return $schedule;
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



    public function evolve(): void
    {
        usort($this->individuals, fn($a, $b) => $b->fitness <=> $a->fitness);
        $fittest = array_slice($this->individuals, 0, count($this->individuals) / 2);

        $newGeneration = [];

        foreach ($fittest as $individual) {
            $newSchedule = $individual->schedule->clone();
            if (rand() / getrandmax() < $this->mutationRate) {
                $newSchedule->shuffle();
            }
            $newGeneration[] = new Individual($newSchedule);
        }

        $this->individuals = array_merge($fittest, $newGeneration);
    }

    public function getFittest(): Individual
    {
        usort($this->individuals, fn($a, $b) => $b->fitness <=> $a->fitness);
        return $this->individuals[0];
    }
}
