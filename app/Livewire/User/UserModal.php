<?php

namespace App\Livewire\User;

use App\Livewire\Forms\UserForm;
use App\Models\Department;
use App\Models\Programme;
use App\Models\Role;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class UserModal extends Component
{
    use WireUiActions;
    protected $listeners = ['openModal'];

    public UserForm $form;

    public array $roleSlugMap = [];

    #[Validate('required|array|min:1')]
    public array $userRoleIds = [];

    public $roles = [];
    public $departments = [];
    public $programmes = [];

    public function mount()
    {
        $this->roleSlugMap = Role::pluck('name', 'id')->mapWithKeys(function ($name, $id) {
            return [$id => strtolower($name)];
        })->toArray();

        $this->roles = Role::all()->toArray();
        $this->departments = Department::all()->toArray();
        $this->programmes = Programme::all()->toArray();
    }

    public function hasRole(string $slug): bool
    {
        foreach ($this->userRoleIds as $roleId) {
            if (($this->roleSlugMap[$roleId] ?? null) === strtolower($slug)) {
                return true;
            }
        }
        return false;
    }

    public function openModal($id = null)
    {
        $this->form->reset();
        $this->form->resetErrorBag();
        $this->userRoleIds = [];

        if ($id) {
            $user = User::findOrFail($id);
            $this->form->userId = $user->id;
            $this->form->first_name = $user->first_name;
            $this->form->last_name = $user->last_name;
            $this->form->email = $user->email;
            $this->form->userRoleIds = $user->roles->pluck('id')->toArray();

            if ($user->hasRole('Student') && $user->student) {
                $this->form->level = $user->student->level;
                $this->form->programme_id = $user->student->programme_id;
            }

            if ($user->hasRole('Lecturer') && $user->lecturer) {

                $this->form->department_id = $user->lecturer->department_id;
            }

            $this->userRoleIds = $this->form->userRoleIds;
        }

        $this->modal()->open('user-modal');
    }

    public function save()
    {
        $this->form->userRoleIds = $this->userRoleIds;
        $this->form->store();
        $this->notification()->success(
            'Saved',
            'User saved successfully.'
        );
        $this->modal()->close('user-modal');
        $this->dispatch('refresh-list');
    }

    public function render()
    {
        return view('livewire.user.user-modal');
    }
}
