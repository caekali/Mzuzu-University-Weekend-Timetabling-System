<?php

namespace App\Services\GeneticAlgorithm;

class Schedule
{
    protected array $scheduleEntries = [];
    private $numberOfHardConflicts = 0;
    private $numberOfSoftConflicts = 0;
    private $fitness = -1;
    private $isFitnessChanged = true;

    public static function generateRandomSchedule($data): Schedule
    {
        $schedule = new Schedule();
        $courses = $data['courses'];
        $venues = $data['venues'];
        $timeSlots = $data['timeslots'];

        foreach ($courses as $course) {
            $sessions = self::parseLectureHours($course->weekly_hours);

            foreach ($sessions as $i => $hours) {
                $slotSet = static::randomConsecutiveTimeSlots($timeSlots, $hours);
                $venue = $venues[array_rand($venues)];
                $key = "{$course->id}-$i";

                $schedule->scheduleEntries[$key] = new ScheduleEntry($course, $course->lecturer_id, $venue, $slotSet, $course->programmes);
            }
        }

        return $schedule;
    }

    private static function parseLectureHours(string $lectureHours): array
    {
        return array_map('intval', explode(',', $lectureHours));
    }

    private static function randomConsecutiveTimeSlots(array $timeSlots, int $length): array
    {
        $max = max(0, count($timeSlots) - $length);
        $attempts = 10;

        while ($attempts-- > 0) {
            $start = rand(0, $max);
            $slice = array_slice($timeSlots, $start, $length);

            if (self::areConsecutive($slice)) {
                return $slice;
            }
        }

        // fallback
        return array_slice($timeSlots, 0, $length);
    }

    private static function areConsecutive(array $slots): bool
    {
        for ($i = 1; $i < count($slots); $i++) {
            if ($slots[$i - 1]['day'] !== $slots[$i]['day']) return false;
            if ((strtotime($slots[$i]['start']) - strtotime($slots[$i - 1]['start'])) !== 3600) return false;
        }
        return true;
    }

    public function getFitness()
    {
        if ($this->isFitnessChanged) {
            $this->fitness = $this->calculateFitness();
            $this->isFitnessChanged = false;
        }
        return $this->fitness;
    }

    public function getNumberOfSoftConflicts()
    {
        return $this->numberOfSoftConflicts;
    }

    public function getNumberOfHardConflicts()
    {
        return $this->numberOfHardConflicts;
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

    private function calculateFitness(): float
    {
        $hardConflicts = 0;
        $softConflicts = 0;

        $weights = [
            'lecturer_conflict' => 1.0,
            'programme_conflict' => 1.0,
            'venue_conflict' => 1.0,
            'venue_overcapacity' => 0.5,
            'tight_clustering_penalty' => 0.3,
        ];

        foreach ($this->scheduleEntries as $i => $entry1) {
            foreach ($this->scheduleEntries as $j => $entry2) {
                if ($i >= $j) continue;

                if ($this->overlaps($entry1->timeSlots, $entry2->timeSlots)) {
                    if ($entry1->course->lecturer_id === $entry2->course->lecturer_id) {
                        $hardConflicts += $weights['lecturer_conflict'];
                    }

                    if (count(array_intersect($entry1->programmes, $entry2->programmes)) > 0) {
                        $hardConflicts += $weights['programme_conflict'];
                    }
                    if ($entry1->venue->id === $entry2->venue->id) {
                        $hardConflicts += $weights['venue_conflict'];
                    }
                }
            }

            // Soft constraint: venue too small
            if ($entry1->course->expected_students > $entry1->venue->capacity) {
                $softConflicts += $weights['venue_overcapacity'];
            }

            // Optional soft constraint: discourage tight clustering of sessions
            // You can implement logic to penalize if a lecturer has too many sessions close together
            // or if students have more than X sessions in a day, etc.
        }

        $this->numberOfHardConflicts = $hardConflicts;
        $this->numberOfSoftConflicts = $softConflicts;

        $totalPenalty = $hardConflicts + $softConflicts;

        // Fitness is inverse of penalty (higher is better)
        return 1 / (1 + $totalPenalty);
    }

    public function getScheduleEntries()
    {
        $this->isFitnessChanged = true;
        return $this->scheduleEntries;
    }

    public function setScheduleEntries(array $scheduleEntries): void
    {
        $this->scheduleEntries = $scheduleEntries;
        $this->isFitnessChanged = true;
    }
}
