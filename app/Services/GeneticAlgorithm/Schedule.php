<?php

namespace App\Services\GeneticAlgorithm;

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


    public static function generateRandomSchedule($data): Schedule
    {
        $schedule = new Schedule($data['constraints'] ?? []);
        $courses = $data['courses'];
        $venues = $data['venues'];
        $timeSlots = $data['timeslots'];

        foreach ($courses as $course) {
            $sessions = self::parseLectureHours($course->lecture_hours);
            $usedDays = [];

            foreach ($sessions as $i => $hours) {
                $slotSet = static::randomConsecutiveTimeSlotsAvoidingDays($timeSlots, $hours, $usedDays);

                // Track used day
                if (!empty($slotSet)) {
                    $usedDays[] = $slotSet[0]['day'];
                }

                $venue = $venues[array_rand($venues)];
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

        return $schedule;
    }

    private static function parseLectureHours(string $lectureHours): array
    {
        return array_map('intval', explode(',', $lectureHours));
    }

    private static function randomConsecutiveTimeSlotsAvoidingDays(array $timeSlots, int $length, array $usedDays): array
    {
        $max = max(0, count($timeSlots) - $length);
        $attempts = 10;

        while ($attempts-- > 0) {
            $start = rand(0, $max);
            $slice = array_slice($timeSlots, $start, $length);

            if (
                self::areConsecutive($slice) &&
                !in_array($slice[0]['day'], $usedDays)
            ) {
                return $slice;
            }
        }

        // Fallback
        foreach ($timeSlots as $start => $slot) {
            if (!in_array($slot['day'], $usedDays)) {
                $slice = array_slice($timeSlots, $start, $length);
                if (self::areConsecutive($slice)) {
                    return $slice;
                }
            }
        }

        return array_slice($timeSlots, 0, $length); // Last resort fallback
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
            'same_day_sessions' => 1.0, // New: Penalty for same-day sessions
        ];

        $courseDayMap = [];

        foreach ($this->scheduleEntries as $i => $entry1) {
            $courseId = $entry1->course->id;
            $day = $entry1->timeSlots[0]['day'] ?? null;

            // Track course sessions by day
            if ($day !== null) {
                if (!isset($courseDayMap[$courseId])) {
                    $courseDayMap[$courseId] = [];
                }

                if (in_array($day, $courseDayMap[$courseId])) {
                    $hardConflicts += $weights['same_day_sessions'];
                }

                $courseDayMap[$courseId][] = $day;
            }

            // constraint checking for each slot
            foreach ($entry1->timeSlots as $slot) {
                $lecturerViolation = $this->getConstraintViolationType('lecturers', $entry1->lecturer, $slot);
                $venueViolation = $this->getConstraintViolationType('venues', $entry1->venue->id, $slot);

                if ($lecturerViolation === 'hard') $hardConflicts++;
                if ($lecturerViolation === 'soft') $softConflicts += 0.5;

                if ($venueViolation === 'hard') $hardConflicts++;
                if ($venueViolation === 'soft') $softConflicts += 0.5;
            }

            // check overlapping
            foreach ($this->scheduleEntries as $j => $entry2) {
                if ($i >= $j) continue;

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

            // Soft constraint: venue too small
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

        foreach ($constraints as $constraint) {
            if (
                $constraint->constraintable_id == $id &&
                $constraint->day === $slot['day'] &&
                strtotime($slot['start']) >= strtotime($constraint->start_time) &&
                strtotime($slot['start']) < strtotime($constraint->end_time)
            ) {
                return $constraint->is_hard ? 'hard' : 'soft';
            }
        }

        return null; // no constraint violated
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
