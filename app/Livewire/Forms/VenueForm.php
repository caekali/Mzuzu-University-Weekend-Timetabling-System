<?php

namespace App\Livewire\Forms;

use App\Models\Venue;
use Livewire\Attributes\Validate;
use Livewire\Form;

class VenueForm extends Form
{
    public $venueId = null;

    #[Validate('required|string|unique:venues,name')]
    public $name = '';

    #[Validate('required|integer')]
    public $capacity = null;

    #[Validate('required|boolean')]
    public $is_lab = false;

    public function store()
    {
        $validated =  $this->validate();

        if (!$this->venueId) {
            Venue::create($validated);
        } else {
            Venue::findOrFail($this->venueId)
                ->update($validated);
        }

        $this->reset();
    }
}
