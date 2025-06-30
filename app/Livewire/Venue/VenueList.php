<?php

namespace App\Livewire\Venue;

use App\Models\Venue;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class VenueList extends Component
{
    use WireUiActions, WithPagination, WithoutUrlPagination;

    public $search = '';

    public $headers  = [
        'name' => 'Name',
        'capacity' => 'Capacity',
    ];

    public function openModal($id = null)
    {
        $this->dispatch('openModal', $id)->to('venue.venue-modal');
    }

    public function confirmDelete($id)
    {
        $this->dialog()->confirm([
            'title'       => 'Delete Venue?',
            'description' => 'Are you sure you want to delete this venue?',
            'icon'        => 'warning',
            'accept'      => [
                'label'  => 'Yes, Delete',
                'method' => 'delete',
                'params' => $id,
            ],
            'reject' => [
                'label'  => 'Cancel',
            ],
        ]);
    }

    public function delete($id)
    {
        Venue::findOrFail($id)->delete();

        $this->notification()->success(
            'Deleted',
            'Venue deleted successfully.'
        );

        $this->dispatch('refresh');
    }

    #[On('refresh')]
    public function refresh() {}

    public function render()
    {
        $venues = Venue::query()
            ->when(
                trim($this->search),
                function ($query) {
                    $query->where(function ($query) {
                        $query->where('name', 'like', '%' . trim($this->search) . '%');
                    });
                }
            )
            ->latest()
            ->paginate(6);
        return view('livewire.venue.venue-list', compact('venues'));
    }
}
