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

    // public function login(LoginRequest $loginRequest)
    // {

    //     if (! $user->is_active) {
    //         return back()->withErrors(['status' => 'Your account is not activated. Please activate first.']);
    //     }

    //     // Attempt authenticating user
    //     if (!Auth::attempt($loginRequest->only('email', 'password'))) {
    //         return back()->withErrors(['status' => 'Invalid credentials'])->withInput();
    //     }

    //     // Success: redirect
    //     $loginRequest->session()->regenerate();
    //     return $this->authenticated();
    // }

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

        $roles = $user->roles->pluck('name');
        // prefer Lecturer default role
        if ($roles->contains('Lecturer')) {
            session(['current_role' => 'Lecturer']);
        } else {
            session(['current_role' => $roles->first()]);
        }

        return redirect()->route('dashboard');
    }
}
