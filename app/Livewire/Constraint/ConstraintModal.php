<?php

namespace App\Livewire\Constraint;

use App\Livewire\Forms\ConstraintForm;
use App\Models\Constraint;
use App\Models\Lecturer;
use App\Models\Venue;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ConstraintModal extends Component
{
    use WireUiActions;
    protected $listeners = ['openModal'];
    public ConstraintForm $form;
    public $constraintable_type = 'lecturer';
    public $constraintable_resources = [];

    public function openModal($id = null, $type)
    {
        // reset prev form
        $this->form->reset();
        $this->form->resetErrorBag();
        $this->constraintable_type = $type;
        if ($this->constraintable_type == 'lecturer') {
            $this->constraintable_resources = Lecturer::with('user')->get()->map(function ($lecturer) {
                return [
                    'id' => $lecturer->id,
                    'name' => $lecturer->user->first_name . ' ' . $lecturer->user->last_name,
                ];
            });
            $this->form->constraintable_type = 'lecturer';
        }

        if ($this->constraintable_type == 'venue') {
            $this->constraintable_resources = Venue::all()->map(function ($venue) {
                return [
                    'id' => $venue->id,
                    'name' => $venue->name,
                ];
            });
            $this->form->constraintable_type = 'venue';
        }

        if ($id) {
            $constraint = Constraint::findOrFail($id);
            $this->form->id = $constraint->id;
            $this->form->day = $constraint->day;
            $this->form->constraintable_id = $constraint->constraintable_id;
            $this->form->start_time = date('H:i', strtotime($constraint->start_time));
            $this->form->end_time =  date('H:i', strtotime($constraint->end_time));
            $this->form->type = $constraint->type;
            $this->form->is_hard = $constraint->is_hard;
        }

        $this->modal()->open('constraint-modal');
    }

    public function save()
    {
        $this->form->store();
        $this->notification()->success(
            'Saved',
            'Constraint saved successfully.'
        );
        $this->modal()->close('constraint-modal');
        $this->dispatch('refresh-list');
    }

    public function render()
    {
        return view('livewire.constraint.constraint-modal');
    }
}
