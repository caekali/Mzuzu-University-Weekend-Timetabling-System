<?php

namespace App\Livewire;

use App\Models\ScheduleDay;
use App\Models\Setting;
use Livewire\Attributes\Validate;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

use function App\Helpers\getSetting;


class Settings extends Component
{
    use WireUiActions;

    #[Validate('required|integer|min:0')]
    public $slotDuration;

    #[Validate('required|integer|min:0')]
    public $breakDuration;

    public $scheduleDays = [];

    public array $originalScheduleDays = [];

    public $hasUnsavedChanges;

    public int $originalSlotDuration;

    public int $originalBreakDuration;

    #[Validate('required')]
    public $standardStartTime;

    #[Validate('required')]
    public $standardEndTime;

    public  $originalStandardStartTime;

    public  $originalStandardEndTime;

    public function mount()
    {
        $this->scheduleDays = ScheduleDay::all()->map(function ($day) {
            return [
                'id' => $day->id,
                'name' => $day->name,
                'enabled' => (bool) $day->enabled,
                'start_time' => $day->start_time,
                'end_time' => $day->end_time,
            ];
        })->toArray();

        $this->slotDuration = getSetting('slot_duration', 60);
        $this->breakDuration = getSetting('break_duration', 10);

        $this->standardStartTime = getSetting('start_time', '07:00');
        $this->standardEndTime = getSetting('end_time', '18:00');

        // original values

        $this->originalStandardStartTime =   $this->standardStartTime;
        $this->originalStandardEndTime =   $this->standardEndTime;

        $this->originalSlotDuration = $this->slotDuration;
        $this->originalBreakDuration = $this->breakDuration;
        $this->originalScheduleDays = $this->scheduleDays;
        $this->hasUnsavedChanges = false;
    }

    public function save()
    {
        foreach ($this->scheduleDays as $schedule) {
            ScheduleDay::where('id', $schedule['id'])->update([
                'enabled' => $schedule['enabled'],
                'start_time' => $schedule['start_time'],
                'end_time' => $schedule['end_time'],
            ]);
        }

        // Save settings
        Setting::updateOrCreate(['key' => 'slot_duration'], ['value' => $this->slotDuration]);
        Setting::updateOrCreate(['key' => 'break_duration'], ['value' => $this->breakDuration]);
        Setting::updateOrCreate(['key' => 'start_time'], ['value' => $this->standardStartTime]);
        Setting::updateOrCreate(['key' => 'end_time'], ['value' => $this->standardEndTime]);

        // Sync originals
        $this->originalScheduleDays = $this->scheduleDays;
        $this->originalSlotDuration = $this->slotDuration;
        $this->originalBreakDuration = $this->breakDuration;
        $this->originalStandardStartTime = $this->standardStartTime;
        $this->originalStandardEndTime =  $this->standardEndTime;

        $this->hasUnsavedChanges = false;

        $this->notification()->success(
            'Updated',
            'Setings updated successfully.'
        );
    }


    public function checkForChanges()
    {
        $this->hasUnsavedChanges =
            $this->scheduleDays !== $this->originalScheduleDays ||
            $this->slotDuration !== $this->originalSlotDuration ||
            $this->breakDuration !== $this->originalBreakDuration ||
            $this->standardStartTime !==  $this->originalStandardStartTime ||
            $this->standardEndTime !==  $this->originalStandardEndTime;
    }

    public function updatedSlotDuration()
    {
        $this->checkForChanges();
    }

    public function updatedBreakDuration()
    {
        $this->checkForChanges();
    }

    public function updatedStandardStartTime()
    {
        $this->checkForChanges();
    }

    public function updatedStandardEndTime()
    {
        $this->checkForChanges();
    }

    public function formatDuration($minutes): string
    {
        $hours = intdiv(intval($minutes) ?? 0, 60);
        $mins = ($minutes ? $minutes : 0) % 60;

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

        if ($this->slotDuration + $this->breakDuration <= 0) {
            return 0;
        }

        return (int)floor($totalMinutes / ($this->slotDuration + $this->breakDuration));
    }
    public function toggleDay($dayId)
    {
        foreach ($this->scheduleDays as $index => $day) {
            if ($day['id'] === $dayId) {
                $this->scheduleDays[$index]['enabled'] = !$day['enabled'];
                break;
            }
        }
        $this->checkForChanges();
    }


    public function updateTime($dayId, $field, $value)
    {
        if (!in_array($field, ['start_time', 'end_time'])) return;

        foreach ($this->scheduleDays as $index => $day) {
            if ($day['id'] === $dayId) {
                $this->scheduleDays[$index][$field] = $value;
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
