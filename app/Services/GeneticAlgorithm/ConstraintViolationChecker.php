<?php

// namespace App\Services\GeneticAlgorithm;

// use App\Models\ScheduleEntry;
// use Illuminate\Database\Eloquent\Collection;

// class ConstraintViolationChecker
// {
//     public function check(array $entries): array
//     {
//         if (empty($entries)) return [];

//         $entryBlockIds = collect($entries)->pluck('id')->all();
//         $ignoreIds = collect($entryBlockIds)->filter()->all();

//         $scheduleVersionId = $entries[0]->schedule_version_id;

//         $allEntries = ScheduleEntry::where('schedule_version_id', $scheduleVersionId)
//             ->when($ignoreIds, fn($q) => $q->whereNotIn('id', $ignoreIds))
//             ->get();

//         return $this->detectConflicts(collect($entries), $allEntries, $entryBlockIds);
//     }

//     public function checkMany(Collection $entries): array
//     {
//         return $this->detectConflicts($entries, $entries, $entries->pluck('id')->all());
//     }


//     private function detectConflicts(Collection $newEntries, Collection $existingEntries, array $entryBlockIds): array
//     {
//         $violations = [];

//         foreach ($newEntries as $entry) {
//             $conflict = $this->findLecturerConflict($entry, $existingEntries);
//             if ($conflict) {
//                 $violations[] = $this->buildViolation('lecturer_conflict', $entryBlockIds, [$conflict->id], $entry);
//                 continue;
//             }

//             $conflict = $this->findProgrammeConflict($entry, $existingEntries);
//             if ($conflict) {
//                 $violations[] = $this->buildViolation('programme_conflict', $entryBlockIds, [$conflict->id], $entry);
//                 continue;
//             }

//             $conflict = $this->findVenueConflict($entry, $existingEntries);
//             if ($conflict) {
//                 $violations[] = $this->buildViolation('venue_conflict', $entryBlockIds, [$conflict->id], $entry);
//             }
//         }

//         return $violations;
//     }

//     private function buildViolation(string $type, array $entryIds, array $conflictIds, $entry): array
//     {
//         return [
//             'type' => $type,
//             'entry_ids' => $entryIds,
//             'conflicting_entry_ids' => $conflictIds,
//             'message' => ucfirst(str_replace('_', ' ', $type)) . " at {$entry->day} {$entry->start_time}"
//         ];
//     }
// }
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


        foreach ($entries as $entry) {
            // Lecturer conflict
            $lecturerConflict = $this->findLecturerConflict($entry, $allEntries);
            if ($lecturerConflict) {
                break;
            }
        }

        if ($lecturerConflict) {
            $violations[] = [
                'entry_ids' => $entryBlockIds,
                'conflicting_entry_ids' => [$lecturerConflict->id],
                'type' => 'lecturer_conflict',
                'message' => "Lecturer conflict at {$lecturerConflict->day} {$lecturerConflict->start_time}",
            ];
        }

        // programme + level conflict
        foreach ($entries as $entry) {
            $programmeConflict = $this->findProgrammeConflict($entry, $allEntries);
            if ($programmeConflict) {
                $violations[] = [
                    'type' => 'programme_conflict',
                    'entry_ids' => [$entry->id],
                    'conflicting_entry_ids' => [$programmeConflict->id],
                    'message' => "Programme conflict at {$entry->day} {$entry->start_time}"
                ];
                break;
            }
        }

        // venue conflict check
        $venueConflict =  $this->findVenueConflict($entry, $allEntries);
        if ($venueConflict) {
            $violations[] = [
                'type' => 'venue_conflict',
                'entry_ids' => [$entry->id],
                'conflicting_entry_ids' => [$venueConflict->id],
                'message' => "Venue conflict at {$entry->day} {$entry->start_time}"
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

            // lecturer conflict
            $lecturerConflict = $this->findLecturerConflict($entry, $others);

            if ($lecturerConflict) {
                $violations[] = [
                    'type' => 'lecturer_conflict',
                    'entry_ids' => [$entry->id],
                    'conflicting_entry_ids' => [$lecturerConflict->id],
                    'message' => "Lecturer conflict at {$entry->day} {$entry->start_time}"
                ];
                continue;
            }

            // programme + level conflict
            $programmeConflict = $this->findProgrammeConflict($entry, $others);
            if ($programmeConflict) {
                $violations[] = [
                    'type' => 'programme_conflict',
                    'entry_ids' => [$entry->id],
                    'conflicting_entry_ids' => [$programmeConflict->id],
                    'message' => "Programme conflict at {$entry->day} {$entry->start_time}"
                ];
                continue;
            }

            // venue conflict check
            $venueConflict =  $this->findVenueConflict($entry, $others);
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


    private function overlaps($a, $b): bool
    {
        return $a->day === $b->day &&
            $a->start_time < $b->end_time &&
            $a->end_time > $b->start_time;
    }

    private function findLecturerConflict($entry, $others)
    {
        return $others->first(
            fn($other) =>
            !$this->isSameSession($entry, $other) &&
                $entry->lecturer_id === $other->lecturer_id &&
                $this->overlaps($entry, $other)
        );
    }

    private function findProgrammeConflict($entry, $others)
    {
        return $others->first(
            fn($other) =>
            !$this->isSameSession($entry, $other) &&
                $entry->programme_id === $other->programme_id &&
                $entry->level === $other->level &&
                $this->overlaps($entry, $other)
        );
    }

    private function findVenueConflict($entry, $others)
    {
        return $others->first(
            fn($other) =>
            !$this->isSameSession($entry, $other) &&
                $entry->venue_id === $other->venue_id &&
                $this->overlaps($entry, $other)
        );
    }

    // only differ in programmes
    private function isSameSession($a, $b): bool
    {
        return $a->course_id === $b->course_id &&
            $a->lecturer_id === $b->lecturer_id &&
            $a->start_time === $b->start_time &&
            $a->end_time === $b->end_time &&
            $a->day === $b->day;
    }
}
