<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class StudentController extends Controller
{

    public function dashboard()
    {
        $user = Auth::user();

        // Get active elections
        $activeElections = Election::where('status', 'active')->get();

        // Check if student has already voted in these elections
        $votedElectionIds = $user->votes()
            ->whereHas('election', function($query) {
                $query->where('status', 'active');
            })
            ->pluck('election_id')
            ->toArray();

        return view('student.dashboard', [
            'activeElections' => $activeElections,
            'votedElectionIds' => $votedElectionIds
        ]);
    }

    public function showElectionCandidates(Election $election)
    {
        // Ensure the election is active
        if ($election->status !== 'active') {
            return redirect()->route('student.dashboard')
                ->with('error', 'This election is not currently active.');
        }

        // Get candidates for this election
        $candidates = Candidate::where('election_id', $election->id)
            ->with('user')
            ->get();

        // Check if student has already voted in this election
        $hasVoted = Vote::where('student_id', Auth::id())
            ->where('election_id', $election->id)
            ->exists();

        return view('student.election-candidates', [
            'election' => $election,
            'candidates' => $candidates,
            'hasVoted' => $hasVoted
        ]);
    }

    public function vote(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'election_id' => 'required|exists:elections,id'
        ]);

        // Check if election is active
        $election = Election::findOrFail($request->election_id);
        if ($election->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This election is not currently active.'
            ], 400);
        }

        // Check if student has already voted
        $existingVote = Vote::where('student_id', $user->id)
            ->where('election_id', $election->id)
            ->first();

        if ($existingVote) {
            return response()->json([
                'success' => false,
                'message' => 'You have already voted in this election.'
            ], 400);
        }

        // Create the vote
        $vote = Vote::create([
            'student_id' => $user->id,
            'candidate_id' => $request->candidate_id,
            'election_id' => $election->id,
            'is_verified' => true // You might want to implement email verification
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your vote has been recorded.'
        ]);
    }
}