<?php

namespace App\Livewire\Programme;

use App\Models\Programme;
use Livewire\Component;
use WireUi\Traits\WireUiActions;
use Livewire\Attributes\On;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ProgrammeList extends Component
{

    use WireUiActions, WithPagination, WithoutUrlPagination;

    public $search = '';
    public   $headers = [
        'code' => 'Code',
        'name' => 'Name',
        'department' => 'Department',
    ];

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
        $programmes = Programme::with('department')
            ->when(trim($this->search), function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . trim($this->search) . '%')
                        ->orWhere('code', 'like', '%' . trim($this->search) . '%');
                });
            })
            ->latest()
            ->paginate(6);

        $programmes->setCollection(
            $programmes->getCollection()->transform(function ($programme) {
                return [
                    'id' => $programme->id,
                    'code' => $programme->code,
                    'name' => $programme->name,
                    'department' => $programme->department->name ?? 'N/A',
                ];
            })
        );
        return view('livewire.programme.programme-list', compact('programmes'));
    }
}
