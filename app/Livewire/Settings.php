<?php

namespace App\Livewire;

use App\Models\DaySchedule;
use App\Models\Setting;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

use function App\Helpers\getSetting;


class Settings extends Component
{
    use WireUiActions;

    public $slot_duration;
    public $break_duration;
    public $daySchedules = [];
    public array $originalDaySchedules = [];
    public $hasUnsavedChanges;
    public int $originalSlotDuration;
    public int $originalBreakDuration;

    public function mount()
    {
        $this->daySchedules = DaySchedule::all()->map(function ($day) {
            return [
                'id' => $day->id,
                'name' => $day->name,
                'enabled' => (bool) $day->enabled,
                'start_time' => $day->start_time,
                'end_time' => $day->end_time,
            ];
        })->toArray();

        $this->slot_duration = getSetting('slot_duration', 60);
        $this->break_duration = getSetting('break_duration', 10);

        // original values
        $this->originalSlotDuration = $this->slot_duration;
        $this->originalBreakDuration = $this->break_duration;
        $this->originalDaySchedules = $this->daySchedules;
        $this->hasUnsavedChanges = false;
    }

    public function save()
    {
        foreach ($this->daySchedules as $schedule) {
            DaySchedule::where('id', $schedule['id'])->update([
                'enabled' => $schedule['enabled'],
                'start_time' => $schedule['start_time'],
                'end_time' => $schedule['end_time'],
            ]);
        }

        // Save settings
        Setting::updateOrCreate(['key' => 'slot_duration'], ['value' => $this->slot_duration]);
        Setting::updateOrCreate(['key' => 'break_duration'], ['value' => $this->break_duration]);

        // Sync originals
        $this->originalDaySchedules = $this->daySchedules;
        $this->originalSlotDuration = $this->slot_duration;
        $this->originalBreakDuration = $this->break_duration;
        $this->hasUnsavedChanges = false;

        $this->notification()->success(
            'Updated',
            'Setings updated successfully.'
        );
    }


    public function checkForChanges()
    {
        $this->hasUnsavedChanges =
            $this->daySchedules !== $this->originalDaySchedules ||
            $this->slot_duration !== $this->originalSlotDuration ||
            $this->break_duration !== $this->originalBreakDuration;
    }

    public function updatedSlotDuration()
    {
        $this->checkForChanges();
    }

    public function updatedBreakDuration()
    {
        $this->checkForChanges();
    }

    public function formatDuration(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        if ($hours > 0) {
            return $mins > 0 ? "{$hours}h {$mins}m" : "{$hours}h";
        }

        return "{$mins}m";
    }

    public function calculateDurationMinutes(array $day): int
    {
        [$sh, $sm] = explode(':', $day['start_time']);
        [$eh, $em] = explode(':', $day['end_time']);

        return ((int)$eh * 60 + (int)$em) - ((int)$sh * 60 + (int)$sm);
    }
    public function calculateTotalSlots($day)
    {
        if (!$day['enabled']) return 0;

        // HH:MM
        [$sh, $sm] = explode(':', $day['start_time']);
        [$eh, $em] = explode(':', $day['end_time']);

        $startMinutes = ((int)$sh * 60) + (int)$sm;
        $endMinutes = ((int)$eh * 60) + (int)$em;
        $totalMinutes = $endMinutes - $startMinutes;

        if ($this->slot_duration + $this->break_duration <= 0) {
            return 0;
        }

        return (int)floor($totalMinutes / ($this->slot_duration + $this->break_duration));
    }
    public function toggleDay($dayId)
    {
        foreach ($this->daySchedules as $index => $day) {
            if ($day['id'] === $dayId) {
                $this->daySchedules[$index]['enabled'] = !$day['enabled'];
                break;
            }
        }
        $this->checkForChanges();
    }


    public function updateTime($dayId, $field, $value)
    {
        if (!in_array($field, ['start_time', 'end_time'])) return;

        foreach ($this->daySchedules as $index => $day) {
            if ($day['id'] === $dayId) {
                $this->daySchedules[$index][$field] = $value;
                break;
            }
        }
        $this->checkForChanges();
    }


    public function render()
    {
        return view('livewire.settings');
    }
}
