<?php

namespace App\Livewire\Forms;

use App\Models\Constraint;
use App\Models\Lecturer;
use App\Models\Venue;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ConstraintForm extends Form
{
    public  $id;
    public $constraintable_type = '';
    public $constraintable_id = null;
    public ?string $day = null;
    public ?string $start_time = null;
    public ?string $end_time = null;
    public string $type = 'unavailable';
    public bool $is_hard = false;

    public function store()
    {
        $validated = $this->validate($this->rules());

        if ($this->id) {
            $constraint = Constraint::findOrFail($this->id);
            $constraint->update($validated);
        } else {
            if ($this->constraintable_type === 'lecturer') {
                $model = Lecturer::findOrFail($this->constraintable_id);
            } elseif ($this->constraintable_type === 'venue') {
                $model = Venue::findOrFail($this->constraintable_id);
            } else {
                throw new \Exception("Unsupported constraintable type: {$this->constraintable_type}");
            }

            $model->constraints()->create($validated);
        }
        $this->reset(['day', 'start_time', 'end_time', 'type', 'is_hard']);
    }


    public function rules(): array
    {
        return [
            'day' => ['required'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'type' => ['required', Rule::in(['unavailable', 'preferred'])],
            'is_hard' => ['boolean'],
            'constraintable_id' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'end_time.after' => 'End time must be after start time.',
            'start_time.required' => 'Please select a start time.',
            'end_time.required' => 'Please select an end time.',
        ];
    }
}
