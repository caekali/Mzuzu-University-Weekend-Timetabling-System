<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (!Auth::check()) {
            return view("auth.login");
        }

        return $this->authenticated();
    }

    public function login(LoginRequest $loginRequest)
    {

        // Attempt authenticating user
        if (!Auth::attempt($loginRequest->only('email', 'password'))) {
            return back()->withErrors(['status' => 'Invalid credentials'])->withInput();
        }

        // Success: redirect
        $loginRequest->session()->regenerate();
        return $this->authenticated();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function authenticated()
    {
        $user = Auth::user();

        $firstRole = Auth::user()->roles->first();
        if ($firstRole) {
            session(['current_role' => $firstRole->name]);
        }

        // role checking and redirect
        if ($user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('HOD')) {
            return redirect()->route('hod.dashboard');
        }

        if ($user->hasRole('Lecturer')) {
            return redirect()->route('lecturer.dashboard');
        }

        if ($user->hasRole('Student')) {
            return redirect()->route('student.dashboard');
        }
    }
}
