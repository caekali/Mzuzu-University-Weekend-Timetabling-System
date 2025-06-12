<?php

namespace App\Services\Scheduling;

class Schedule
{
    public array $entries = [];

    public function addEntry(array $entry): void
    {
        $this->entries[] = $entry;
    }

    public function getEntries(): array
    {
        return $this->entries;
    }

    public function clone(): Schedule
    {
        $new = new self();
        $new->entries = $this->entries;
        return $new;
    }

    public function shuffle(): void
    {
        shuffle($this->entries);
    }

    //  Check if a given venue or lecturer is already booked at the specified time slot
     
    public function isSlotTaken(int $resourceId, int $timeSlot, string $resourceType = 'venue'): bool
    {
        foreach ($this->entries as $entry) {
            if ($resourceType === 'venue' && $entry['venue_id'] === $resourceId && $entry['time_slot'] === $timeSlot) {
                return true;
            }

            if ($resourceType === 'lecturer' && $entry['lecturer_id'] === $resourceId && $entry['time_slot'] === $timeSlot) {
                return true;
            }
        }
        return false;
    }
}
