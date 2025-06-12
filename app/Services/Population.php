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
}
