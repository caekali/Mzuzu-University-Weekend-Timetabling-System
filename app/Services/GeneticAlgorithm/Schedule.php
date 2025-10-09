<?php

namespace App\Services\GeneticAlgorithm;

use Illuminate\Support\Facades\Log;

class Schedule
{
    protected array $scheduleEntries = [];

    private $numberOfHardConflicts = 0;

    private $numberOfSoftConflicts = 0;

    private $fitness = -1;

    private $isFitnessChanged = true;

    protected array $constraints = [];

    public function __construct(array $constraints = [])
    {
        $this->constraints = $constraints;
    }

    public static function generateRandomSchedule(array $data): Schedule
    {

        $schedule = new Schedule($data['constraints']);
        $courses = $data['courses'];
        $venues = $data['venues'];
        $timeSlots = $data['timeslots'];

        foreach ($courses as $course) {
            $sessions = self::parseLectureHours($course->lecture_hours);

            foreach ($sessions as $i => $hours) {
                $slotSet = self::findConsecutiveTimeSlots($timeSlots, $hours);

                if (! empty($slotSet)) {
                    shuffle($venues);
                    $venue = $venues[0];
                    $key = "{$course->id}-$i-$course->level";

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
            ->filter(fn ($s) => ($s['type'] ?? 'session') === 'session')
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

                if (! empty($set)) {
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

        return ! empty($validSets) ? $validSets[array_rand($validSets)] : [];
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
            'same_day_sessions' => 1.0,
        ];


        foreach ($this->scheduleEntries as $i => $entry1) {
            foreach ($entry1->timeSlots as $slot) {
                $lecturerViolation = $this->getConstraintViolationType('lecturers', $entry1->lecturer, $slot);
                $venueViolation = $this->getConstraintViolationType('venues', $entry1->venue->id, $slot);


                if ($venueViolation === 'hard') {
                    $hardConflicts++;
                }
                if ($venueViolation === 'soft') {
                    $softConflicts++;
                }
            }

            foreach ($this->scheduleEntries as $j => $entry2) {
                if ($i >= $j) {
                    continue;
                }

                if ($this->overlaps($entry1->timeSlots, $entry2->timeSlots)) {
                    if ($entry1->course->lecturer_id === $entry2->course->lecturer_id) {
                        $hardConflicts += $weights['lecturer_conflict'];
                    }

                    if (count(array_intersect($entry1->programmes, $entry2->programmes)) > 0 && ($entry1->level == $entry2->level)) {
                        $hardConflicts += $weights['programme_conflict'];
                    }

                    if ($entry1->venue->id === $entry2->venue->id) {
                        $hardConflicts += $weights['venue_conflict'];
                    }
                }
            }

            if ($entry1->course->expected_students > $entry1->venue->capacity) {
                $softConflicts += $weights['venue_overcapacity'];
            }
        }

        $this->numberOfHardConflicts = $hardConflicts;
        $this->numberOfSoftConflicts = $softConflicts;

        $totalPenalty = $hardConflicts + $softConflicts;

        return 1 / (1 + $totalPenalty);
    }

    private function getConstraintViolationType($type, $id, $slot): ?string
    {
        $constraints = $this->constraints[$type] ?? collect();

        // Log::debug("Checking constraints for type={$type}, id={$id}, slot=" . json_encode($slot));

        foreach ($constraints as $constraint) {
            // Log::debug('Evaluating constraint', [
            //     'constraint_id' => $constraint->id ?? null,
            //     'constraintable_id' => $constraint->constraintable_id,
            //     'day' => $constraint->day,
            //     'start_time' => $constraint->start_time,
            //     'end_time' => $constraint->end_time,
            //     'is_hard' => $constraint->is_hard,
            // ]);

            if (
                $constraint->constraintable_id == $id &&
                $constraint->day === $slot['day'] &&
                strtotime($slot['start']) >= strtotime($constraint->start_time) &&
                strtotime($slot['end']) < strtotime($constraint->end_time)
            ) {
                $type = $constraint->is_hard ? 'hard' : 'soft';

                // Log::debug('Evaluating constraint', [
                //     'constraint_id' => $constraint->id ?? null,
                //     'constraintable_id' => $constraint->constraintable_id,
                //     'day' => $constraint->day,
                //     'start_time' => $constraint->start_time,
                //     'end_time' => $constraint->end_time,
                //     'is_hard' => $constraint->is_hard,
                // ]);

                // Log::info('Constraint violation detected', [
                //     'constraint_type' => $type,
                //     'slot' => $slot,
                //     'constraint_id' => $constraint->id ?? null,
                // ]);

                return $type;
            }
        }

        // Log::debug("No constraint violation for id={$id}, slot=" . json_encode($slot));

        return null;
    }

    // private function getConstraintViolationType($type, $id, $slot): ?string
    // {
    //     $constraints = $this->constraints[$type] ?? collect();

    //     foreach ($constraints as $constraint) {
    //         if (
    //             $constraint->constraintable_id == $id &&
    //             $constraint->day === $slot['day'] &&
    //             strtotime($slot['start']) >= strtotime($constraint->start_time) &&
    //             strtotime($slot['end']) < strtotime($constraint->end_time)
    //         ) {
    //             return $constraint->is_hard ? 'hard' : 'soft';
    //         }
    //     }

    //     return null;
    // }

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

    public function copy(): self
    {
        $copy = new self($this->constraints);
        $copy->setScheduleEntries($this->scheduleEntries);

        return $copy;
    }
}
