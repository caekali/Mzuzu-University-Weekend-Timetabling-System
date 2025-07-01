<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $currentRole;

    public function mount()
    {
        $this->currentRole = session('current_role');
    }

    public function logout()
    {
        Auth::logout();
        $this->session()->invalidate();
        $this->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
