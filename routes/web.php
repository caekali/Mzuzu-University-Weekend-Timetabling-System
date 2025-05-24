<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name("login");
    Route::post('/', [LoginController::class, 'login'])->name("login");
    Route::get('/contact-admin', [ContactController::class, 'show'])->name('contact.admin');
    Route::post('/contact-admin', [ContactController::class, 'send']);
});

Route::prefix('password')->group(function () {
    Route::get("/forget-password", function () {
        
        return view('auth.forget-password');
    })->name("password.forget-password");

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name("logout");
        Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name("change-password");
    });
});


Route::prefix("admin")->middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::prefix("lecturer")->middleware(['auth', 'role:Lecturer'])->group(function () {
    Route::get('dashboard', function () {
        return view('lecturer.dashboard');
    })->name('lecturer.dashboard');
});

Route::prefix("hod")->middleware(['auth', 'role:HOD'])->group(function () {
    Route::get('dashboard', function () {
        return view('hod.dashboard');
    })->name('hod.dashboard');
});

Route::prefix("student")->middleware(['auth', 'role:Student'])->group(function () {

    Route::get("account-setup", function () {
        return view('student.account-setup');
    })->name("student.account-setup");

    Route::get('dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');

    Route::get('schedules', function () {
        return view('student.schedules');
    })->name('student.schedules');

    Route::get('profile', function () {
        return view('student.profile');
    })->name('student.profile');
});
