<?php

namespace App\Livewire\Forms;

use App\Models\Venue;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class VenueForm extends Form
{
    public $venueId = null;

    #[Validate('required|string')]
    public $name = '';

    #[Validate('required|integer')]
    public $capacity = null;

    #[Validate('required|boolean')]
    public $is_lab = false;

    public function store()
    {
        $rules = [
            'name' => [
                'required',
                'string',
                Rule::unique('venues', 'name')->ignore($this->venueId),
            ],
            'capacity' => 'required|integer',
            'is_lab' => 'required|boolean',
        ];

        $validated = $this->validate($rules);

        Venue::updateOrCreate(
            ['id' => $this->venueId],
            $validated
        );

        $this->reset();
    }
}
