<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class StudentMiddleware
{
    public function handle($request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login first');
        }

        // Check if user is a student
        if (Auth::user()->isAdmin()) {
            return redirect('/admin/dashboard')->with('error', 'Unauthorized access');
        }

        return $next($request);
    }
}
