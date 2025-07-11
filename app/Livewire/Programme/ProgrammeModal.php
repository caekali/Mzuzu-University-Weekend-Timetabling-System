<?php

namespace App\Livewire\Programme;

use App\Livewire\Forms\ProgrammeForm;
use App\Models\Department;
use App\Models\Programme;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ProgrammeModal extends Component
{
    use WireUiActions;
    protected $listeners = ['openModal'];

    public ProgrammeForm $form;
    public function openModal($id = null)
    {
        $this->form->reset();
        $this->form->resetErrorBag();

        if ($id) {
            $programme = Programme::findOrFail($id);
            $this->form->programmeId = $programme->id;
            $this->form->code = $programme->code;
            $this->form->name = $programme->name;
            $this->form->number_of_students = $programme->number_of_students;
            $this->form->department_id = $programme->department_id;
        }

        $this->modal()->open('programme-modal');
    }

    public function save()
    {
        $this->form->store();
        $this->notification()->success(
            'Saved',
            'Programme saved successfully.'
        );
        $this->modal()->close('programme-modal');
        $this->dispatch('refresh-list');
    }
    public function render()
    {
        return view('livewire.programme.programme-modal', [
            'departments' => Department::all()
        ]);
    }
}
