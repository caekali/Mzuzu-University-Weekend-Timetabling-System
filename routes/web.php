<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ChangePasswordController;

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name("login");
    Route::post('/', [LoginController::class, 'login'])->name("login");
});

Route::prefix('password')->group(function () {
    Route::get("/password-assistance", function () {
        return view('auth.password-assistance');
    })->name("password.password-assistance");

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name("logout");
        Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name("change-password");
    });
});



Route::prefix("admin")->middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

Route::prefix("lecturer")->middleware(['auth', 'role:Lecturer'])->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])
        ->name('lecturer.dashboard');
});

Route::prefix("hod")->middleware(['auth', 'role:HOD'])->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])
        ->name('hod.dashboard');
});

Route::prefix("student")->middleware(['auth', 'role:Student'])->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])
        ->name('student.dashboard');
    Route::get("account-setup", function () {
        return view('student.account-setup');
    })->name("student.account-setup");
});


//
Route::get("/student-dashboard", function () {
    return view('student.student-dashboard');
})->name("student-dashboard");

Route::get('/student-dashboard', function () {
    return view('student.student-dashboard');
})->name('my schedule');

Route::get('/student-dashboard', function () {
    return view('student.student-dashboard');
})->name('dashboard');

Route::get('/student-dashboard/profile', function () {
    return view('student.profile');
})->name('profile');
