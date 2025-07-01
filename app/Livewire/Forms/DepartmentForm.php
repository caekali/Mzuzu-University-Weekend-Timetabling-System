<?php

namespace App\Livewire\Forms;

use App\Models\Department;
use Livewire\Attributes\Validate;
use Livewire\Form;


class DepartmentForm extends Form
{

    public $departmentId = null;

    #[Validate('required|string')]
    public $code = '';

    #[Validate('required|string')]
    public $name = '';

    public function store()
    {
        $validated =  $this->validate();

        if (!$this->departmentId) {
            Department::create($validated);
        } else {
            Department::findOrFail($this->departmentId)
                ->update($validated);
        }
        $this->reset(['departmentId', 'code', 'name']);
        $this->reset();
    }
}
