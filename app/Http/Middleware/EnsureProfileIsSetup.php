<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsSetup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            if (
                ($user->hasRole('Student') && is_null($user->student)) ||
                ($user->hasRole('Lecturer') && is_null($user->lecturer))
            ) {
                // URL to return after setup
                session(['setup_redirect_url' => url()->current()]);

                return redirect()->route('profile.setup');
            }
        }
        return $next($request);
    }
}
