<?php

namespace App\Services\GeneticAlgorithm;


use App\Models\ScheduleEntry;
use Illuminate\Database\Eloquent\Collection;

class ConstraintViolationChecker
{
    public function check(array $entries): array
    {
        $violations = [];
        $ignoreIds = collect($entries)->pluck('id')->filter()->all();

        $violations = [];

        if (empty($entries)) {
            return [];
        }
        $entryBlockIds = collect($entries)->pluck('id')->all();

        $scheduleVersionId = $entries[0]->schedule_version_id;

        $allEntries = ScheduleEntry::where('schedule_version_id', $scheduleVersionId)
            ->when($ignoreIds, fn($q) => $q->whereNotIn('id', $ignoreIds))
            ->get();

        $conflictingLecturer = null;
        $conflictingProgramme = null;

        foreach ($entries as $entry) {
            // Lecturer conflict
            $conflictingLecturer = $allEntries->first(function ($existing) use ($entry) {
                return $existing->lecturer_id === $entry->lecturer_id
                    && $existing->day === $entry->day
                    && $existing->start_time < $entry->end_time
                    && $existing->end_time > $entry->start_time
                    && $existing->id !== $entry->id
                    && !(
                        $existing->course_id === $entry->course_id &&
                        $existing->programme_id === $entry->programme_id &&
                        $existing->level === $entry->level
                    );
            });

            if ($conflictingLecturer) {
                break;
            }
        }

        if ($conflictingLecturer) {
            $violations[] = [
                'entry_ids' => $entryBlockIds,
                'conflicting_entry_ids' => [$conflictingLecturer->id],
                'type' => 'lecturer_conflict',
                'message' => "Lecturer conflict at {$conflictingLecturer->day} {$conflictingLecturer->start_time}",
            ];
        }

        foreach ($entries as $entry) {
            $conflictingProgramme = $allEntries->first(function ($existing) use ($entry) {
                return $existing->programme_id === $entry->programme_id
                    && $existing->day === $entry->day
                    && $existing->start_time < $entry->end_time
                    && $existing->end_time > $entry->start_time
                    && $existing->id !== $entry->id
                    &&  $existing->level === $entry->level
                    && !(
                        $existing->course_id === $entry->course_id
                    );
            });

            if ($conflictingProgramme) {
                break;
            }
        }

        if ($conflictingProgramme) {
            $violations[] = [
                'entry_ids' => $entryBlockIds,
                'conflicting_entry_ids' => [$conflictingProgramme->id],
                'type' => 'programme_conflict',
                'message' => "Programme conflict at {$conflictingProgramme->day} {$conflictingProgramme->start_time}",
            ];
        }

        return $violations;
    }
    public function checkMany(Collection $entries): array
    {
        $violations = [];

        foreach ($entries as $entry) {
            // Filter out the current entry
            $others = $entries->filter(fn($e) => $e->id !== $entry->id);

            $overlaps = fn($a, $b) =>
            $a->day === $b->day &&
                $a->start_time < $b->end_time &&
                $a->end_time > $b->start_time;

            //check if two entries are part of same "session block"
            $isSameSession = fn($a, $b) =>
            $a->course_id === $b->course_id &&
                $a->lecturer_id === $b->lecturer_id &&
                $a->day === $b->day &&
                $a->start_time === $b->start_time &&
                $a->end_time === $b->end_time;

            // === LECTURER CONFLICT ===
            $lecturerConflict = $others->first(
                fn($other) =>
                !$isSameSession($entry, $other) &&
                    $other->lecturer_id === $entry->lecturer_id &&
                    $overlaps($entry, $other)
            );

            if ($lecturerConflict) {
                $violations[] = [
                    'type' => 'lecturer_conflict',
                    'entry_ids' => [$entry->id],
                    'conflicting_entry_ids' => [$lecturerConflict->id],
                    'message' => "Lecturer conflict at {$entry->day} {$entry->start_time}"
                ];
                continue;
            }

            // === PROGRAMME CONFLICT ===
            $programmeConflict = $others->first(
                fn($other) =>
                !$isSameSession($entry, $other) &&
                    $other->programme_id === $entry->programme_id &&
                    $other->level === $entry->level &&
                    $overlaps($entry, $other)
            );

            if ($programmeConflict) {
                $violations[] = [
                    'type' => 'programme_conflict',
                    'entry_ids' => [$entry->id],
                    'conflicting_entry_ids' => [$programmeConflict->id],
                    'message' => "Programme conflict at {$entry->day} {$entry->start_time}"
                ];
                continue;
            }

            // === VENUE CONFLICT ===
            $venueConflict = $others->first(
                fn($other) =>
                !$isSameSession($entry, $other) &&
                    $other->venue_id === $entry->venue_id &&
                    $overlaps($entry, $other)
            );

            if ($venueConflict) {
                $violations[] = [
                    'type' => 'venue_conflict',
                    'entry_ids' => [$entry->id],
                    'conflicting_entry_ids' => [$venueConflict->id],
                    'message' => "Venue conflict at {$entry->day} {$entry->start_time}"
                ];
            }
        }

        return $violations;
    }
}
