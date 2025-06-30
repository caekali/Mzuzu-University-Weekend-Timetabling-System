<?php

namespace App\Livewire\Venue;

use App\Livewire\Forms\VenueForm;
use App\Models\Venue;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class VenueModal extends Component
{
    use WireUiActions;
    protected $listeners = ['openModal'];

    public VenueForm $form;

    public function openModal($id = null)
    {
        $this->form->reset();
        $this->form->resetErrorBag();

        if ($id) {
            $venue = Venue::findOrFail($id);
            $this->form->venueId = $venue->id;
            $this->form->name = $venue->name;
            $this->form->capacity = $venue->capacity;
            $this->form->is_lab = $venue->is_lab;
        }

        $this->modal()->open('venue-modal');
    }

    public function save()
    {
        $this->form->store();
        $this->notification()->success(
            'Saved',
            'Venue saved successfully.'
        );
        $this->modal()->close('venue-modal');
        $this->dispatch('refresh');
    }
    public function render()
    {
        return view('livewire.venue.venue-modal');
    }
}
