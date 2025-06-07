<?php

namespace App\Livewire\Department;

use App\Models\Department;
use Livewire\Component;
use Livewire\Attributes\On;
use WireUi\Traits\WireUiActions;

class DepartmentList extends Component
{
    use WireUiActions;

    public string $search = '';

    public function openModal($id = null)
    {
        $this->dispatch('openModal', $id)->to('department.department-modal');
    }

    public function confirmDelete($id)
    {
        $this->dialog()->confirm([
            'title'       => 'Delete Department?',
            'description' => 'Are you sure you want to delete this department?',
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
        Department::findOrFail($id)->delete();

        $this->notification()->success(
            'Deleted',
            'Department deleted successfully.'
        );

        $this->dispatch('refresh-list');
    }

    #[On('refresh-list')]
    public function refresh() {}

    public function render()
    {
        $departments = Department::query()
            ->when(
                $this->search,
                fn($query) =>
                $query->where('name', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->get();
        return view('livewire.department.department-list', [
            'departments' => $departments,
        ]);
    }
}
