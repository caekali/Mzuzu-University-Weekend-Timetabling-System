<?php

namespace App\Livewire;

use App\Models\Constraint;
use App\Models\Venue;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ConstraintList extends Component
{

    use WireUiActions;
    public $constraints = [];

    public $headers = [
        'id' => 'ID',
        'name' => 'name',
        'day' => 'Day',
        'time' => 'time',
        'type' => 'Type',
        'is_hard' => 'Hard?'
    ];

    public function mount()
    {
        $this->constraints = Constraint::with('constraintable')
            ->where('constraintable_type', Venue::class)
            ->get()
            ->map(function ($constraint) {
                return [
                    'id' => $constraint->id,
                    'day' => $constraint->day,
                    'time' => Carbon::parse($constraint->start_time)->format('H:i') . ' - ' .
                        Carbon::parse($constraint->end_time)->format('H:i'),
                    'type' => $constraint->type,
                    'is_hard' => $constraint->is_hard ? 'Yes' : 'No',
                    'name' => optional($constraint->constraintable)->name, // safeguard
                ];
            });
    }

    public function openModal($id = null)
    {
        $this->dispatch('openModal', $id)->to('constraint-modal');
    }

    public function confirmDelete($id)
    {
        $this->dialog()->confirm([
            'title'       => 'Delete Constraint?',
            'description' => 'Are you sure you want to delete this Constraint?',
            'icon'        => 'warning',
            'accept'      => [
                'label'  => 'Yes, Delete',
                'method' => 'delete',
                'params' => $id,
            ],
            'reject' => [
                'label'  => 'Cancel',
            ],
        ]);
    }

    public function delete($id)
    {
        Constraint::findOrFail($id)->delete();

        $this->notification()->success(
            'Deleted',
            'Constraint deleted successfully.'
        );

        $this->dispatch('refresh-list');
    }

    #[On('refresh-list')]
    public function refresh() {}

    public function render()
    {
        return view('livewire.constraint-list');
    }
}
