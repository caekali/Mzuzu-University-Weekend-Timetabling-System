<?php

namespace App\Livewire\Programme;

use App\Models\Programme;
use Livewire\Component;
use WireUi\Traits\WireUiActions;
use Livewire\Attributes\On;

class ProgrammeList extends Component
{

    use WireUiActions;

    public $search = '';

    public function openModal($id = null)
    {
        $this->dispatch('openModal', $id)->to('programme.programme-modal');
    }

    public function confirmDelete($id)
    {
        $this->dialog()->confirm([
            'title'       => 'Delete Programme?',
            'description' => 'Are you sure you want to delete this programme?',
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
        Programme::findOrFail($id)->delete();

        $this->notification()->success(
            'Deleted',
            'Programme deleted successfully.'
        );

        $this->dispatch('refresh-list');
    }

    #[On('refresh-list')]
    public function refresh() {}

    public function render()
    {
        $programmes = Programme::with('department')->latest()->get();

        return view('livewire.programme.programme-list', [
            'programmes' => $programmes
        ]);
    }
}
