<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and an admin 
        $user = Session::get('name');
        $userRoles = Session::get('roles');
        $authentication = Session::get('authentication');
        if (!$authentication || !$user || !$userRoles || ($authentication === false)) {
            // Redirect to the login route if not authenticated or user_id is not set
            return redirect()->route('auth.login')->with('error', 'Login to continue!');
        }
        foreach ($userRoles as $role) {
            if ($role == 'C') { // futher change the role for admin users
                Log::info("The user has an administrator role!");
                return $next($request);
            } else {
                Log::warning("The user does not have an administrator role!");
                return redirect()->route('auth.login')->with('error', 'Unauthorized access!');
            }
        }
    }
}
