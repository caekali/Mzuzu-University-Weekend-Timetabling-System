<?php

namespace App\Livewire\Forms;

use App\Models\Programme;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProgrammeForm extends Form
{
    public $programmeId = null;

    #[Validate('required|string|max:10')]
    public $code = '';

    #[Validate('required|string')]
    public $name = '';

    #[Validate('required|integer|min:0')]
    public $number_of_students;

    #[Validate('required|exists:departments,id')]
    public $department_id = '';

    public function store()
    {
        $rules = [
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('programmes', 'code')->ignore($this->programmeId),
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('programmes', 'name')->ignore($this->programmeId),
            ],
            'department_id' => 'required|exists:departments,id',
            'number_of_students' => 'required|integer|min:0'
        ];

        $validated = $this->validate($rules);

        Programme::updateOrCreate(
            ['id' => $this->programmeId],
            $validated
        );

        $this->reset();
        $this->resetValidation();
    }
}
