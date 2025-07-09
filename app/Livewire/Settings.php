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

    public function mount()
    {
        $this->scheduleDays = ScheduleDay::all()
        ->sortByDesc('enabled')
        ->map(function ($day) {
            return [
                'id' => $day->id,
                'name' => $day->name,
                'enabled' => (bool) $day->enabled,
                'start_time' => $day->start_time,
                'end_time' => $day->end_time,
            ];
        })
        ->values()
        ->toArray();

        $this->slotDuration = getSetting('slot_duration', 60);
        $this->breakDuration = getSetting('break_duration', 30);

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

        Setting::updateOrCreate(['key' => 'slot_duration'], ['value' => $this->slotDuration]);
        Setting::updateOrCreate(['key' => 'break_duration'], ['value' => $this->breakDuration]);

        $this->originalScheduleDays = $this->scheduleDays;
        $this->originalSlotDuration = $this->slotDuration;
        $this->originalBreakDuration = $this->breakDuration;
        $this->hasUnsavedChanges = false;

        $this->notification()->success(
            'Updated',
            'Settings updated successfully.'
        );
    }

    public function checkForChanges()
    {
        $this->hasUnsavedChanges =
            $this->scheduleDays !== $this->originalScheduleDays ||
            $this->slotDuration !== $this->originalSlotDuration ||
            $this->breakDuration !== $this->originalBreakDuration;
    }

    public function updatedSlotDuration()
    {
        $this->checkForChanges();
    }

    public function updatedBreakDuration()
    {
        $this->checkForChanges();
    }

    public function updatedScheduleDays()
    {
        $this->checkForChanges();
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

    public function formatDuration($minutes): string
    {
        $hours = intdiv((int)($minutes ?? 0), 60);
        $mins = ($minutes ?? 0) % 60;

        if ($hours > 0) {
            return $mins > 0 ? "{$hours}h {$mins}m" : "{$hours}h";
        }

        return "{$mins}m";
    }

    public function calculateDurationMinutes(array $day): int
    {
        if (empty($day['start_time']) || empty($day['end_time'])) {
            return 0;
        }

        [$sh, $sm] = explode(':', $day['start_time']);
        [$eh, $em] = explode(':', $day['end_time']);

        return ((int)$eh * 60 + (int)$em) - ((int)$sh * 60 + (int)$sm);
    }

    public function calculateTotalSlots($day)
    {
        if (
            !$day['enabled'] ||
            empty($day['start_time']) ||
            empty($day['end_time']) ||
            ($this->slotDuration + $this->breakDuration <= 0)
        ) {
            return 0;
        }

        [$sh, $sm] = explode(':', $day['start_time']);
        [$eh, $em] = explode(':', $day['end_time']);

        $startMinutes = ((int)$sh * 60) + (int)$sm;
        $endMinutes = ((int)$eh * 60) + (int)$em;
        $totalMinutes = $endMinutes - $startMinutes;

        return (int)floor($totalMinutes / ($this->slotDuration + $this->breakDuration));
    }

    public function render()
    {
        return view('livewire.settings');
    }
}
