<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSwitchController extends Controller
{
    public function switch($role)
    {
        $user = Auth::user();

        // Validate the requested role
        if (! $user->roles->pluck('name')->contains($role)) {
            abort(403, 'Unauthorized role switch.');
        }

        // Update the session with the new role
        session(['current_role' => $role]);

        $route = '';

        // set route based on switched role
        if ($role == 'Admin') {
            $route = 'admin.dashboard';
        }

        if ($role == 'HOD') {
            $route = 'hod.dashboard';
        }

        if ($role == 'Lecturer') {
            $route = 'lecturer.dashboard';
        }

        if ($role == 'Student') {
            $route = 'student.dashboard';
        }
        // redirect based on route according to role
        return redirect()->route($route)->with('success', 'Switched to ' . ucfirst($role));
    }
}
