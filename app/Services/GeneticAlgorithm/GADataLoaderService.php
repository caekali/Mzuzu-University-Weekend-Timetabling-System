<?php

namespace App\Services\GeneticAlgorithm;

use App\Models\Venue;
use App\DTO\VenueDTO;
use App\DTO\CourseDTO;
use App\Models\Constraint;
use App\Models\Lecturer;
use App\Models\ScheduleDay;
use App\Models\LecturerCourseAllocation;


use function App\Helpers\getSetting;

class GADataLoaderService
{

    public function loadGAData(): array
    {
        return [
            'venues'    => $this->getVenues(),
            'courses'   => $this->getCourses(),
            'timeslots' => $this->generateTimeslots(),
            'constraints' => $this->getConstraints()
        ];
    }

    protected function getVenues(): array
    {
        return Venue::select('id', 'name', 'capacity')
            ->get()
            ->map(fn($v) => new VenueDTO($v->id, $v->name, $v->capacity))
            ->toArray();
    }

    protected function getCourses(): array
    {
        $allocations = LecturerCourseAllocation::with([
            'lecturer',
            'course',
            'programmes'
        ])->get();

        $courseData = $allocations->map(function ($allocation) {
            $programmes = $allocation->programmes->pluck('id')->toArray();
            $number_of_students = $allocation->programmes->sum('number_of_students');
            return new CourseDTO(
                $allocation->course->id,
                $allocation->course->code,
                $allocation->course->lecture_hours,
                $number_of_students,
                $allocation->lecturer->id,
                $allocation->level,
                $programmes
            );
        })->toArray();

        return $courseData;
    }


    // protected  function generateTimeslots(): array
    // {
    //     $slots = [];
    //     $days = ScheduleDay::where('enabled', true)->get();
    //     $slotMinutes = getSetting('slot_duration');
    //     $breakDuration = getSetting('slot_break');

    //     foreach ($days as $day) {
    //         $current = strtotime($day->start_time);
    //         $endTime = strtotime($day->end_time);
    //         while ($current < $endTime) {
    //             $next = strtotime("+{$slotMinutes} minutes", $current);
    //             if ($next > $endTime) {
    //                 break;
    //             }
    //             $slots[] = [
    //                 'day' => $day->name,
    //                 'start' => date('H:i', $current),
    //                 'end' => date('H:i', $next),
    //             ];

    //             $current = $next;
    //         }
    //     }
    //     return $slots;
    // }
    //  public function generateTimeslots(): array
    // {
    //     $slots = [];
    //     $days = ScheduleDay::where('enabled', true)->get();
    //     $breakDuration = intval(getSetting('break_duration', 30));

    //     foreach ($days as $day) {
    //         $start = \Carbon\Carbon::createFromTimeString($day->start_time);
    //         $end = \Carbon\Carbon::createFromTimeString($day->end_time);
    //         $continuousMinutes = 0;

    //         while ($start->lt($end)) {
    //             $remaining = $start->diffInMinutes($end);

    //             // Insert break after exactly 180 minutes of continuous sessions
    //             if ($continuousMinutes === 180 && $remaining >= $breakDuration) {
    //                 $breakEnd = $start->copy()->addMinutes($breakDuration);
    //                 $slots[] = [
    //                     'day' => $day->name,
    //                     'start' => $start->format('H:i'),
    //                     'end' => $breakEnd->format('H:i'),
    //                     'type' => 'break',
    //                 ];
    //                 $start = $breakEnd;
    //                 $continuousMinutes = 0;
    //                 continue;
    //             }

    //             // Determine optimal slot duration
    //             $preferredDuration = 60; // Default to 60-minute sessions

    //             // If we're within 30 minutes of needing a break, adjust duration
    //             if ($continuousMinutes >= 150 && $remaining >= 30) {
    //                 $preferredDuration = min(30, $remaining);
    //             }

    //             // If we're at :30 after a break, do a 30-minute session
    //             $minutesPastHour = intval($start->format('i'));
    //             if ($continuousMinutes === 0 && $minutesPastHour === 30 && $remaining >= 30) {
    //                 $preferredDuration = 30;
    //             }

    //             // Final duration adjustment
    //             $slotDuration = min($preferredDuration, $remaining);
    //             $slotDuration = min($slotDuration, 180 - $continuousMinutes);

    //             $slotEnd = $start->copy()->addMinutes($slotDuration);
    //             $slots[] = [
    //                 'day' => $day->name,
    //                 'start' => $start->format('H:i'),
    //                 'end' => $slotEnd->format('H:i'),
    //                 'type' => 'session',
    //             ];

    //             $continuousMinutes += $slotDuration;
    //             $start = $slotEnd;
    //         }
    //     }

    //     return $slots;
    // }



    public function generateTimeslots(): array
    {
        $slots = [];
        $days = ScheduleDay::where('enabled', true)->get();
        $breakDuration = intval(getSetting('break_duration', 30));

        foreach ($days as $day) {
            $start = \Carbon\Carbon::createFromTimeString($day->start_time);
            $end = \Carbon\Carbon::createFromTimeString($day->end_time);
            $continuousMinutes = 0;

            while ($start->lt($end)) {
                $remaining = $start->diffInMinutes($end);

                // Insert break after exactly 180 minutes of continuous sessions
                if ($continuousMinutes === 180 && $remaining >= $breakDuration) {
                    $breakEnd = $start->copy()->addMinutes($breakDuration);
                    $slots[] = [
                        'day' => $day->name,
                        'start' => $start->format('H:i'),
                        'end' => $breakEnd->format('H:i'),
                        'type' => 'break',
                    ];
                    $start = $breakEnd;
                    $continuousMinutes = 0;
                    continue;
                }

                // Calculate minutes past the hour
                $minutesPastHour = intval($start->format('i'));

                // After a break, if we're at :30, create a 30-minute session
                if ($continuousMinutes === 0 && $minutesPastHour === 30 && $remaining >= 30) {
                    $slotEnd = $start->copy()->addMinutes(30);
                    $slots[] = [
                        'day' => $day->name,
                        'start' => $start->format('H:i'),
                        'end' => $slotEnd->format('H:i'),
                        'type' => 'session',
                    ];
                    $continuousMinutes += 30;
                    $start = $slotEnd;
                    continue;
                }

                // Default to 60-minute sessions, but adjust if needed
                $slotDuration = min(60, $remaining);

                // If this session would push us over 180 continuous minutes, adjust
                if ($continuousMinutes + $slotDuration > 180) {
                    $slotDuration = 180 - $continuousMinutes;
                }

                // If we're left with a small fragment (<30), extend previous session
                if ($slotDuration < 30 && !empty($slots)) {
                    $lastSlot = end($slots);
                    if ($lastSlot['day'] === $day->name && $lastSlot['type'] === 'session') {
                        array_pop($slots);
                        $lastSlot['end'] = $start->copy()->addMinutes($slotDuration)->format('H:i');
                        $slots[] = $lastSlot;
                        $continuousMinutes += $slotDuration;
                        $start->addMinutes($slotDuration);
                        continue;
                    }
                }

                $slotEnd = $start->copy()->addMinutes($slotDuration);
                $slots[] = [
                    'day' => $day->name,
                    'start' => $start->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                    'type' => 'session',
                ];

                $continuousMinutes += $slotDuration;
                $start = $slotEnd;
            }
        }

        return $slots;
    }
    protected function getConstraints(): array
    {
        return [
            'lecturers' => Constraint::where('constraintable_type', Lecturer::class)->get(),
            'venues' => Constraint::where('constraintable_type', Venue::class)->get(),
        ];
    }
}
