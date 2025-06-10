<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;

class ActivateAccount extends Component
{
    public $userId;

    #[Validate('required|min:8|confirmed')]
    public $password;

    #[Validate('required')]
    public $password_confirmation;

    public function mount($userId)
    {
        if (!request()->hasValidSignature()) {
            abort(403, 'Invalid or expired activation link.');
        }

        $user = User::findOrFail($userId);

        if ($user->is_active) {
            redirect()->route('login')->with('status', 'Account already activated.');
        }

        $this->userId = $userId;
    }

    public function activate()
    {
        $this->validate();

        $user = User::findOrFail($this->userId);

        if ($user->is_active) {
            session()->flash('status', 'Account already activated.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->password),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('login')->with('status', 'Account activated, You can now sign in.');
    }

    public function render()
    {
        return view('livewire.auth.activate-account')->layout('components.layouts.guest');
    }
}
