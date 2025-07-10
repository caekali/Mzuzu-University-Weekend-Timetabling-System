<?php

namespace App\Livewire\Forms;

use App\Models\Department;
use Illuminate\Validation\Rule;
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
        $rules = [
            'code' => [
                'required',
                'string',
                Rule::unique('departments', 'code')->ignore($this->departmentId),
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('departments', 'name')->ignore($this->departmentId),
            ],
        ];

        $validated = $this->validate($rules);

        Department::updateOrCreate(
            ['id' => $this->departmentId],
            $validated
        );

        $this->reset(['departmentId', 'code', 'name']);
    }
}
