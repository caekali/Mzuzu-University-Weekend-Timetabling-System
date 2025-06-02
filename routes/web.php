<?php


use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\ProgrammeController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ActivationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\RoleSwitchController;
use App\Http\Controllers\ConstraintController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimetableController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('shared.dashboard');
    })->name('dashboard');
});


Route::middleware('guest')->group(function () {
    // Route::get('/', [LoginController::class, 'showLoginForm'])->name('auth.login');
    // Route::post('/', [LoginController::class, 'login'])->name('auth.login.submit');


// Request form to trigger activation email
Route::get('/activate', [ActivationController::class, 'requestForm'])->name('auth.account.activation');
Route::post('/activate', [ActivationController::class, 'sendActivationLink'])->name('auth.account.activation.send');

// Set password via signed URL
Route::get('/activatee/{user}', [ActivationController::class, 'showActivationForm'])->name('auth.account.activate.form')->middleware('signed');
Route::post('/activatee/{user}', [ActivationController::class, 'activate'])->name('auth.account.activate');

});




Route::middleware('auth')->group(function () {
    // Route::post('/logout', [LoginController::class, 'logout'])->name('auth.logout');
    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('auth.change-password');

    Route::get('/dashboard', fn() => view('shared.dashboard'))->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
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
    Route::post('/timetable/generate', [TimetableController::class, 'generate'])->name('timetable.generate');

    Route::resource('/departments', DepartmentController::class)->names([
        'index'   => 'departments.index',
        'create'  => 'departments.create',
        'store'   => 'departments.store',
        'show'    => 'departments.show',
        'edit'    => 'departments.edit',
        'update'  => 'departments.update',
        'destroy' => 'departments.destroy',
    ]);

    Route::resource('/courses', CourseController::class)->names([
        'index'   => 'courses.index',
        'create'  => 'courses.create',
        'store'   => 'courses.store',
        'show'    => 'courses.show',
        'edit'    => 'courses.edit',
        'update'  => 'courses.update',
        'destroy' => 'courses.destroy',
    ]);

    Route::resource('/programmes', ProgrammeController::class)->names([
        'index'   => 'programmes.index',
        'create'  => 'programmes.create',
        'store'   => 'programmes.store',
        'show'    => 'programmes.show',
        'edit'    => 'programmes.edit',
        'update'  => 'programmes.update',
        'destroy' => 'programmes.destroy',
    ]);

    Route::resource('/venues', VenueController::class)->names([
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
