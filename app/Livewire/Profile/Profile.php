<?php

namespace App\Livewire\Profile;

use App\Livewire\Forms\PasswordUpdateForm;
use App\Livewire\Forms\UpdateProfileForm;
use App\Models\Department;
use App\Models\Programme;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Profile extends Component
{
    use WireUiActions;

    public PasswordUpdateForm $form;

    public UpdateProfileForm $profile;

    public $studentLevel;

    public $isEditingProfile = false;

    public $isEditingPassword = false;

    public $departments = [];

    public $programmes = [];

    public function mount()
    {
        $user = auth()->user();

        $this->profile->setUser($user);

        if (session('current_role') == 'Student') {
            $student =  Auth::user()->student;
            $this->studentLevel = $student->level;
        }

        if (session('current_role') === 'Lecturer') {
            $this->departments = Department::all();
        }

        if (session('current_role') === 'Student') {
            $this->programmes = Programme::all();
        }
    }

    public function updatePassword()
    {
        if ($this->form->update()) {
            $this->notification()->success(
                'Password Updated',
                'Your password has been successfully updated.'
            );
        }
    }

    public function toggleEditProfile()
    {
        $this->isEditingProfile = !$this->isEditingProfile;
    }

    public function toggleEditPassword()
    {
        $this->isEditingPassword = !$this->isEditingPassword;
    }

    public function updateProfile()
    {
        $this->profile->update();

        $this->isEditingProfile = false;

        $this->notification()->success(
            'Profile',
            'Profile updated successfully'
        );
    }

    public function render()
    {
        return view('livewire.profile.profile');
    }
}
