<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ChangePasswordController;

use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'showLoginForm'])->name("login");
Route::post('/', [LoginController::class, 'login'])->name("login");
Route::post('/logout', [LoginController::class, 'logout'])->name("logout");
Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name("change-password");

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
});

Route::get("/password-assistance", function () {
    return view('auth.password-assistance');
})->name("password-assistance");

Route::get("/account-setup", function () {
    return view('students.account-setup');
})->name("account-setup");

Route::get("/student-dashboard", function () {
    return view('students.student-dashboard');
})->name("student-dashboard");

Route::get('/student-dashboard', function () {
    return view('students.student-dashboard');
})->name('my schedule');

Route::get('/student-dashboard', function () {
    return view('students.student-dashboard');
})->name('dashboard');

Route::get('/student-dashboard/profile', function () {
    return view('students.profile');
})->name('profile');
