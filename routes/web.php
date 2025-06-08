<?php

use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\ProgrammeController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ActivationController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\RoleSwitchController;
use App\Http\Controllers\ConstraintController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimetableController;
use App\Livewire\Auth\ActivateAccount;
use App\Livewire\Course\CourseList;
use App\Livewire\Dashboard;
use App\Livewire\Department\DepartmentList;
use App\Livewire\Profile\Profile;
use App\Livewire\Profile\ProfileSetup;
use App\Livewire\Programme\ProgrammeList;
use App\Livewire\Venue\VenueList;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('shared.dashboard');
//     })->name('dashboard');
// });


Route::middleware('guest')->group(function () {
    Route::get('/activate/request', [ActivationController::class, 'requestForm'])->name('activation.request');
    Route::post('/activate/request', [ActivationController::class, 'sendActivationLink'])->name('activation.email.send');

    Route::get('/activate/{userId}', ActivateAccount::class)
        ->middleware('signed')
        ->name('activation.form');

    // Set password via signed URL
    // Route::get('/activate/{user}', [ActivationController::class, 'showActivationForm'])->name('activation.form')->middleware('signed');
    // Route::post('/activate/{user}', [ActivationController::class, 'activate'])->name('activation.complete');
});




Route::middleware('auth')->group(function () {
    // Route::post('/logout', [LoginController::class, 'logout'])->name('auth.logout');
    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('auth.change-password');

    // Route::get('/dashboard', fn() => view('shared.dashboard'))->name('dashboard');
    // Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/timetable', fn() => view('shared.timetable'))->name('timetable');

    // Universal Timetable Filtering
    //  Route::get('/timetable', [TimetableController::class, 'index']); // filter by programme, level, day, etc.
    // Personal Schedules
    // Route::get('/schedules', [ScheduleController::class, 'mySchedules']); // varies by role

    Route::get('/switch-role/{role}', [RoleSwitchController::class, 'switch'])->name('auth.switch-role');
});


Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/timetable/generate', [TimetableController::class, 'showTimetableGeneratioPage'])->name('timetable.generate');
    Route::post('/timetable/generate', [TimetableController::class, 'generate'])->name('timetable.generate.send');

    Route::resource('/departmentss', DepartmentController::class)->names([
        'index'   => 'departments.index',
        'create'  => 'departments.create',
        'store'   => 'departments.store',
        'show'    => 'departments.show',
        'edit'    => 'departments.edit',
        'update'  => 'departments.update',
        'destroy' => 'departments.destroy',
    ]);

    Route::resource('/coursesss', CourseController::class)->names([
        'index'   => 'courses.index',
        'create'  => 'courses.create',
        'store'   => 'courses.store',
        'show'    => 'courses.show',
        'edit'    => 'courses.edit',
        'update'  => 'courses.update',
        'destroy' => 'courses.destroy',
    ]);

    Route::resource('/programmess', ProgrammeController::class)->names([
        'index'   => 'programmes.index',
        'create'  => 'programmes.create',
        'store'   => 'programmes.store',
        'show'    => 'programmes.show',
        'edit'    => 'programmes.edit',
        'update'  => 'programmes.update',
        'destroy' => 'programmes.destroy',
    ]);

    Route::resource('/venuess', VenueController::class)->names([
        'index'   => 'venues.index',
        'create'  => 'venues.create',
        'store'   => 'venues.store',
        'show'    => 'venues.show',
        'edit'    => 'venues.edit',
        'update'  => 'venues.update',
        'destroy' => 'venues.destroy',
    ]);

    Route::resource('/constraints', ConstraintController::class)->names([
        'index'   => 'constraints.index',
        'create'  => 'constraints.create',
        'store'   => 'constraints.store',
        'show'    => 'constraints.show',
        'edit'    => 'constraints.edit',
        'update'  => 'constraints.update',
        'destroy' => 'constraints.destroy',
    ]);

    Route::get('/users', [UserController::class, 'allUsers'])->name('users.index');
    Route::get('/students', fn() => view('admin.users.students'))->name('users.students');
    Route::get('/lecturers', fn() => view('admin.users.lecturers'))->name('users.lecturers');
});


Route::middleware(['auth', 'role:HOD'])->group(function () {});


Route::middleware(['auth', 'role:Student'])->group(function () {
    Route::get('/profile/setup', fn() => view('student.account-setup'))->name('student.profile.setup');
    Route::put('/profile/setup', [ProfileController::class, 'setupAccount']); // for students

});




// Laravel Livewire based views
Route::get('/profile/setup', ProfileSetup::class)
    ->middleware(['auth'])
    ->name('profile.setup');
Route::middleware(['auth', 'profile.setup'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/courses', CourseList::class)->name('courses');
    Route::get('/departments', DepartmentList::class)->name('departments');
    Route::get('/programmes', ProgrammeList::class)->name('programmes');
    Route::get('/venues', VenueList::class)->name('venues');
    Route::get('/profile', Profile::class)->name('profile');
});
