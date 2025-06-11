<?php

namespace App\Livewire\User;

use App\Models\Role;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class UserList extends Component
{

    use WireUiActions, WithPagination, WithoutUrlPagination;

    public $activeTab = 'users';

    public $userRoleFilter = '';

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function openModal($id = null)
    {
        $this->dispatch('openModal', $id)->to('user.user-modal');
    }

    public function confirmDelete($id)
    {
        $this->dialog()->confirm([
            'title'       => 'Delete User?',
            'description' => 'Are you sure you want to delete this user?',
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
        User::findOrFail($id)->delete();

        $this->notification()->success(
            'Deleted',
            'User deleted successfully.'
        );

        $this->dispatch('refresh-list');
    }

    #[On('refresh-list')]
    public function refresh() {}

    public function render()
    {
        $query = User::query()->with(['roles']);

        if (!empty($this->userRoleFilter)) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->userRoleFilter);
            });

            $slug = strtolower($this->userRoleFilter);
            if ($slug === 'student') {
                $query->with('student');
            } elseif ($slug === 'lecturer') {
                $query->with('lecturer');
            }
        }

        $users = $query->paginate(8);

        $users->getCollection()->transform(function ($user) {
            $data = [
                'id'    => $user->id,
                'name'  => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
            ];

            $data['roles'] = $user->roles->pluck('name')->join(', ');

            if ($this->userRoleFilter == 'Student') {
                $data['level'] = $user->student->level ?? '-';
                $data['programme'] = $user->student->programme->name ?? '-';
            }

            if ($this->userRoleFilter == 'Lecturer') {
                $data['department'] = $user->lecturer->department->name ?? '-';
            }
            return $data;
        });

        $roles = Role::all()->pluck('name');
        return view('livewire.user.user-list', compact('users', 'roles'));
    }
}
