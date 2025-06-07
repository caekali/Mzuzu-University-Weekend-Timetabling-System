<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SendAccountActivation;
use App\Http\Controllers\Controller;



class ActivationController extends Controller
{
    public function requestForm()
    {
        return view('auth.request-activation-link');
    }

    public function sendActivationLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

      
        if ($user->is_active) {
            return back()->withErrors(['status' => 'Account already activated.']);
        }

        $user->notify(new SendAccountActivation($user));

        return back()->with('status', 'Activation link has been sent to your email.');
    }

    public function showActivationForm(Request $request, $userId)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired activation link.');
        }

        $user = User::findOrFail($userId);

        if ($user->is_active) {
            return redirect()->route('login')->withErrors(['status' => 'Account already activated.']);
        }

        return view('auth.set-password', ['user' => $user]);
    }

    public function activate(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        Validator::make($request->all(), [
            'password' => ['required', 'confirmed', 'min:8'],
        ])->validate();

        $user->update([
            'password' => Hash::make($request->password),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('login');
    }
}
