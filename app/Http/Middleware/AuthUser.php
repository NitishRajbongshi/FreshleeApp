<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuthUser
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
        // Check if the user is authenticated
        $user = Session::get('name');
        $userPhone = Session::get('phone');
        $authentication = Session::get('authentication');
        Log::info("Authentication status: " . $authentication);
        if (!$authentication || ($authentication === false)) {
            // Redirect to the login route if not authenticated or user_id is not set
            return redirect()->route('auth.login')->with('error', 'Login to continue!');
        } else {
            if (!$user || !$userPhone) {
                // Redirect to the login route if not authenticated or user_id is not set
                return redirect()->route('auth.login')->with('error', 'Login to continue!');
            }
            return $next($request);
        }
    }
}
