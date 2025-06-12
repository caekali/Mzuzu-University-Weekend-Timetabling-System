<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use WireUi\Traits\WireUiActions;

class RoleSwitcher extends Component
{
    use WireUiActions;
    public $roles;
    public $currentRole;

    public function mount()
    {
        $this->roles = Auth::user()->roles->pluck('name')->toArray();
        $this->currentRole = session('current_role');
    }

    public function switchRole($role)
    {
        if (!in_array($role, $this->roles)) {
            $this->notification()->error(
                $title = 'Role Switch Failed',
                $description = 'You do not have this role.'
            );

            $this->dispatchBrowserEvent('close-dropdown');
            return;
        }

        session(['current_role' => $role]);
        $this->currentRole = $role;

        $this->notification()->success(
            $title = 'Role Switched',
            $description = 'You are now using the role: ' . ucfirst($role)
        );

        $this->dispatchBrowserEvent('close-dropdown'); // 👈 Notify Alpine to close
    }


    public function render()
    {
        return view('livewire.role-switcher');
    }
}
