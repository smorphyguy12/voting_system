<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login first');
        }

        // Check if user is an admin
        if (!Auth::user()->isAdmin()) {
            Auth::logout();
            return redirect('/login')->with('error', 'Unauthorized access');
        }

        return $next($request);
    }
}