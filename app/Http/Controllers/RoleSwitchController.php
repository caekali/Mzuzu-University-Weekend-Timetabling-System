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

        // redirect to dashboard
        return redirect()->route('dashboard')->with('success', 'Switched to ' . ucfirst($role));
    }
}
