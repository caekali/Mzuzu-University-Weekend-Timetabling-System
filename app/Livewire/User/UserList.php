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

    public $userStatusFilter = '';


    public $search = '';

    public $roles;

    public $statusCounts = [];

    public $userStatus;

    public function mount()
    {

        $this->userStatus =  collect(['Active', 'Not Active']);

        $this->roles = Role::all()->pluck('name');

        $this->statusCounts = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'deactivated' => User::where('is_active', false)->count(),
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function openModal($id = null)
    {
        $this->dispatch('openModal', $id)->to('user.user-modal');
    }

    public function confirmUserDeactivation($id)
    {
        $this->dialog()->confirm([
            'title'       => 'Deactivate User?',
            'description' => 'Are you sure you want to deactivate this user?',
            'icon'        => 'warning',
            'accept'      => [
                'label'  => 'Yes, Deactivate',
                'method' => 'deactivate',
                'params' => $id,
            ],
            'reject' => [
                'label'  => 'Cancel',
            ],
        ]);
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = false;
        $user->save();
        $this->notification()->success(
            'User Deactivation',
            'User deactivated successfully.'
        );

        $this->dispatch('refresh-list');
    }

    public function confirmUserActivation($id)
    {
        $this->dialog()->confirm([
            'title'       => 'Activate User?',
            'description' => 'Are you sure you want to activate this user?',
            'icon'        => 'warning',
            'accept'      => [
                'label'  => 'Yes, Activate',
                'method' => 'activate',
                'params' => $id,
            ],
            'reject' => [
                'label'  => 'Cancel',
            ],
        ]);
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = true;
        $user->save();
        $this->notification()->success(
            'User Activation',
            'User activated successfully.'
        );

        $this->dispatch('refresh-list');
    }

    #[On('refresh-list')]
    public function refresh() {}

    public function render()
    {
        $query = User::query()->with(['roles']);

        $query->when(
            trim($this->search),
            function ($query) {
                $query->where(function ($query) {
                    $query->where('first_name', 'like', '%' . trim($this->search) . '%')
                        ->orWhere('last_name', 'like', '%' . trim($this->search) . '%')
                        ->orWhere('email', 'like', '%' . trim($this->search) . '%');
                });
            }
        );

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

        if (!empty($this->userStatusFilter)) {
            $query->where('is_active', $this->userStatusFilter == 'Active' ? 1 : 0);
        }

        $users = $query->paginate(8);
        $users->onEachSide(1);

        $users->getCollection()->transform(function ($user) {
            $data = [
                'id'    => $user->id,
                'name'  => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'status' => $user->is_active ? 'Active' : 'Not Active'
            ];

            $data['roles'] = $user->roles->pluck('name');

            if ($this->userRoleFilter == 'Student') {
                $data['level'] = $user->student->level ?? '-';
                $data['programme'] = $user->student->programme->name ?? '-';
            }

            if ($this->userRoleFilter == 'Lecturer') {
                $data['department'] = $user->lecturer->department->name ?? '-';
            }
            return $data;
        });



        return view('livewire.user.user-list', compact('users'));
    }
}
