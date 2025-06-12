<?php
namespace App\Services\GeneticAlgorithm;

class Schedule
{
    /** @var ScheduleEntry[] */
    public array $entries = [];

    public float $fitness = 0;

    public static function random(array $courses, array $venues, array $timeSlots): self
    {
        $schedule = new self();

        foreach ($courses as $course) {
            $venue = $venues[array_rand($venues)];
            $timeSlot = $timeSlots[array_rand($timeSlots)];

            $schedule->entries[] = new ScheduleEntry($course, $venue, $timeSlot);
        }

        return $schedule;
    }

    public function calculateFitness(): float
    {
        $penalty = 0;

        $timeslotLecturers = [];
        $timeslotVenues = [];
        $timeslotProgrammes = [];

        foreach ($this->entries as $entry) {
            $key = $entry->timeSlot['day'] . '_' . $entry->timeSlot['start'];

            $lecturerId = $entry->course->lecturer_id;
            $venueId = $entry->venue->id;

            // Lecturer conflict
            if (isset($timeslotLecturers[$key][$lecturerId])) {
                $penalty += 10;
            }
            $timeslotLecturers[$key][$lecturerId] = true;

            // Venue conflict
            if (isset($timeslotVenues[$key][$venueId])) {
                $penalty += 10;
            }
            $timeslotVenues[$key][$venueId] = true;

            // Programme conflict
            foreach ($entry->course->programmes as $programme) {
                if (isset($timeslotProgrammes[$key][$programme->id])) {
                    $penalty += 10;
                }
                $timeslotProgrammes[$key][$programme->id] = true;
            }

            // Capacity
            if ($entry->venue->capacity < $entry->course->expected_students) {
                $penalty += 5;
            }
        }

        $this->fitness = 1 / (1 + $penalty);
        return $this->fitness;
    }

    public function crossover(Schedule $partner): Schedule
    {
        $child = new Schedule();
        $mid = floor(count($this->entries) / 2);

        foreach ($this->entries as $i => $entry) {
            $child->entries[] = $i < $mid ? $entry : $partner->entries[$i];
        }

        return $child;
    }

    public function mutate(array $venues, array $timeSlots): void
    {
        $i = array_rand($this->entries);
        if (rand(0, 1)) {
            $this->entries[$i]->timeSlot = $timeSlots[array_rand($timeSlots)];
        } else {
            $this->entries[$i]->venue = $venues[array_rand($venues)];
        }
    }
}

