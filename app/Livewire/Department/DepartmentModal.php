<?php

namespace App\Livewire\Department;

use App\Livewire\Forms\DepartmentForm;
use App\Models\Department;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class DepartmentModal extends Component
{
    use WireUiActions;

    public DepartmentForm $form;

    protected $listeners = ['openModal'];

    public function openModal($id = null)
    {
        $this->form->reset();
        $this->resetErrorBag();

        if ($id) {
            $department = Department::findOrFail($id);
            $this->form->departmentId = $department->id;
            $this->form->code = $department->code;
            $this->form->name = $department->name;
        }

        $this->modal()->open('department-modal');
    }

    public function save()
    {
        $this->form->store();
        $this->notification()->success(
            'Saved',
            'Department saved successfully.'
        );
        $this->modal()->close('department-modal');
        $this->dispatch('refresh-list');
    }

    public function render()
    {
        return view('livewire.department.department-modal');
    }
}
