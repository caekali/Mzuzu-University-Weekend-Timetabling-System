<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ProfileSetupForm extends Form
{
    public string $profileType = '';

    public ?string $department_id = null;

    public ?string $programme_id = null;

    public ?int $level = null;

    public function save()
    {
        $this->validate($this->rules());

        $user = auth()->user();

        match ($this->profileType) {
            'student' => $user->student()->create([
                'programme_id' => $this->programme_id,
                'level' => $this->level,
            ]),
            'lecturer' => $user->lecturer()->create([
                'department_id' => $this->department_id,
            ]),
        };
    }

    public function rules()
    {
        $rules = [];

        if ($this->profileType === 'student') {
            $rules['level'] = 'required|integer|min:1|max:10';
            $rules['programme_id'] = 'required|exists:departments,id';
        }

        if (in_array($this->profileType, ['lecturer', 'hod'])) {
            $rules['department_id'] = 'required|exists:programmes,id';
        }

        return $rules;
    }
}
