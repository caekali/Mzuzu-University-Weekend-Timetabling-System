<?php

namespace App\Services\GeneticAlgorithm;

class Schedule
{
    public array $scheduleEntries = []; // Array of ScheduleEntry instances
    public ?float $fitness = null;

    public function __construct(array $scheduleEntries = [])
    {
        $this->scheduleEntries = $scheduleEntries;
    }

    public static function random(array $courses, array $venues, array $timeSlots): self
    {
        $scheduleEntries = [];

        foreach ($courses as $course) {
            $venue = $venues[array_rand($venues)];
            $timeSlot = $timeSlots[array_rand($timeSlots)];

            $scheduleEntries[] = new ScheduleEntry(
                course: $course,
                lecturer: $course->lecturer, // Assuming each course has one lecturer
                venue: $venue,
                timeSlot: $timeSlot
            );
        }

        return new self($scheduleEntries);
    }

    public function calculateFitness(FitnessEvaluator $evaluator): float
    {
        $this->fitness = $evaluator->evaluate($this);
        return $this->fitness;
    }

    public function mutate(array $venues, array $timeSlots): void
    {
        // Example: Randomly change timeslot or venue for a random entry
        $index = array_rand($this->scheduleEntries);
        $entry = $this->scheduleEntries[$index];

        if (rand(0, 1)) {
            $entry->timeSlot = $timeSlots[array_rand($timeSlots)];
        } else {
            $entry->venue = $venues[array_rand($venues)];
        }

        $this->scheduleEntries[$index] = $entry;
    }

    public function crossover(Schedule $partner): Schedule
    {
        $childEntries = [];
        $midpoint = (int)(count($this->scheduleEntries) / 2);

        for ($i = 0; $i < count($this->scheduleEntries); $i++) {
            $childEntries[] = $i < $midpoint
                ? $this->scheduleEntries[$i]
                : $partner->scheduleEntries[$i];
        }

        return new Schedule($childEntries);
    }





     public function getEntries(): array
    {
        return $this->entries;
    }

    public function setFitness(int $value): void
    {
        $this->fitness = $value;
    }

    public function getFitness(): int
    {
        return $this->fitness;
    }
}
