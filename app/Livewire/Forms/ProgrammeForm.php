<?php

namespace App\Livewire\Forms;

use App\Models\Programme;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProgrammeForm extends Form
{
    public $programmeId = null;

    #[Validate('required|string|max:10')]
    public $code = '';

    #[Validate('required|string')]
    public $name = '';

    #[Validate('required|exists:departments,id')]
    public $department_id = '';

     public function store()
    {
        $validated = $this->validate();

        if (!$this->programmeId) {
            Programme::create($validated);
        } else {
            Programme::findOrFail($this->programmeId)
                ->update($validated);
        }

        $this->reset('form');
        $this->resetValidation();
    }
}
