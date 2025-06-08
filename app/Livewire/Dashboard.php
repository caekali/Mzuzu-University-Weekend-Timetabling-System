<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public $currentRole;
    
    public function mount()
    {
        $this->currentRole = session('current_role');
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
