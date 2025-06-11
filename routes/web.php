<?php

use App\Http\Controllers\GAController;
use App\Http\Controllers\RoleSwitchController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\ActivateAccount;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\RequestActivationLink;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Course\CourseList;
use App\Livewire\Dashboard;
use App\Livewire\Department\DepartmentList;
use App\Livewire\Profile\Profile;
use App\Livewire\Profile\ProfileSetup;
use App\Livewire\Programme\ProgrammeList;
use App\Livewire\Timetable\GenerateTimetable;
use App\Livewire\User\UserList;
use App\Livewire\Venue\VenueList;
use Illuminate\Support\Facades\Auth;


Route::get('/switch-role/{role}', [RoleSwitchController::class, 'switch'])->name('auth.switch-role');

Route::get('/',[GAController::class,'run']);
// Laravel + Livewire based views
// Route::get('/', function () {
//     return Auth::check()
//         ? redirect()->route('dashboard')
//         : redirect()->route('login');
// })->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');

    Route::get('/activate/request', RequestActivationLink::class)->name('activation.request');
    Route::get('/activate/{userId}', ActivateAccount::class)
        ->middleware('signed')
        ->name('activation.form');
});

Route::middleware('auth')->group(function () {
  

    Route::get('/profile/setup', ProfileSetup::class)->name('profile.setup');
});

Route::middleware(['auth', 'profile.setup'])->group(function () {
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/courses', CourseList::class)->name('courses');
    Route::get('/departments', DepartmentList::class)->name('departments');
    Route::get('/programmes', ProgrammeList::class)->name('programmes');
    Route::get('/venues', VenueList::class)->name('venues');
    Route::get('/users', UserList::class)->name('users');
    Route::get('/timetable', UserList::class)->name('timetable');
    Route::get('/timetable/generate', GenerateTimetable::class)->name('timetable.generate');
});
