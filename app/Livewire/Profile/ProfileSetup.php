<?php

namespace App\Livewire\Profile;

use App\Livewire\Forms\ProfileSetupForm;
use App\Models\Department;
use App\Models\Programme;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ProfileSetup extends Component
{
    use WireUiActions;

    public ProfileSetupForm $form;
    public $departments;
    public $programmes;

    public function mount()
    {
        $user = Auth::user();
        if (
            ($user->hasRole('Student') && $user->student) ||
            ($user->hasRole('Lecturer') && $user->lecturer)
        ) {
            return redirect()->to(route('dashboard'));
        }

        // Setup form type
        if ($user->hasRole('Student')) {
            $this->form->profileType = 'student';
            $this->programmes = Programme::all();
        } elseif ($user->hasRole('Lecturer')) {
            $this->departments =  Department::all();
            $this->form->profileType = 'lecturer';
        } else {
            abort(403, 'Invalid role for setup.');
        }
    }
    public function save()
    {
        $this->form->save();
        $this->notification()->success(
            title: 'Profile Setup Complete',
            description: 'Your profile has been set up.'
        );

        return redirect()->to(session('setup_redirect_url', route('dashboard')));
    }
    public function render()
    {
        return view('livewire.profile.profile-setup')->layout('components.layouts.guest');
    }
}
