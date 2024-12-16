<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Vote;

class PreventMultipleVotesMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        
        // Check if user has already voted in the current election
        $existingVote = Vote::where('student_id', $user->id)
            ->whereHas('election', function($query) {
                $query->where('status', 'active');
            })
            ->first();

        if ($existingVote) {
            return response()->json([
                'error' => 'You have already voted in the current election.'
            ], 403);
        }

        return $next($request);
    }
}