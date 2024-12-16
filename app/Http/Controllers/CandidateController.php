<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::with(['user', 'election'])
            ->latest()
            ->paginate(10);

        return view('admin.candidates.index', compact('candidates'));
    }

    public function create()
    {
        $elections = Election::where('status', '!=', 'closed')->get();
        $eligibleStudents = User::whereDoesntHave('candidates', function ($query) {
            $query->whereHas('election', function ($subQuery) {
                $subQuery->where('status', '!=', 'closed');
            });
        })->where('role_id', Role::where('name', 'student')->first()->id)
            ->get();

        return view('admin.candidates.create', [
            'elections' => $elections,
            'eligibleStudents' => $eligibleStudents
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                // Ensure student is not already a candidate in active/upcoming elections
                function ($attribute, $value, $fail) use ($request) {
                    $existingCandidate = Candidate::whereHas('election', function ($query) {
                        $query->whereIn('status', ['active', 'upcoming']);
                    })->where('user_id', $value)
                        ->where('election_id', $request->election_id)
                        ->exists();

                    if ($existingCandidate) {
                        $fail('This student is already a candidate in the selected election.');
                    }
                }
            ],
            'election_id' => 'required|exists:elections,id',
            'candidate_role' => 'required|in:President,Vice President,Secretary,Treasurer',
            'platform' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048' // 2MB max
        ]);

        // Handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('candidate_avatars', 'public');
        }

        // Create candidate
        $candidate = Candidate::create([
            'user_id' => $validatedData['user_id'],
            'election_id' => $validatedData['election_id'],
            'candidate_role' => $validatedData['candidate_role'],
            'platform' => $validatedData['platform'] ?? null,
            'avatar' => $avatarPath
        ]);

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate registered successfully');
    }

    public function show(Candidate $candidate)
    {
        // Detailed candidate view with vote analytics
        $voteCount = $candidate->votes()->count();
        $votePercentage = $this->calculateVotePercentage($candidate);

        return view('admin.candidates.show', [
            'candidate' => $candidate,
            'voteCount' => $voteCount,
            'votePercentage' => $votePercentage
        ]);
    }

    private function calculateVotePercentage(Candidate $candidate)
    {
        $totalVotes = $candidate->election->votes()->count();
        $candidateVotes = $candidate->votes()->count();

        return $totalVotes > 0
            ? round(($candidateVotes / $totalVotes) * 100, 2)
            : 0;
    }

    public function edit(Candidate $candidate)
    {
        $elections = Election::where('status', '!=', 'closed')->get();
        $students = User::all();

        return view('admin.candidates.edit', [
            'candidate' => $candidate,
            'elections' => $elections,
            'students' => $students
        ]);
    }

    public function destroy(Candidate $candidate)
    {
        try {
            if ($candidate->profile_picture) {
                Storage::disk('public')->delete($candidate->profile_picture);
            }

            $candidate->delete();

            return response()->json([
                'success' => true,
                'message' => 'Candidate deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting candidate: ' . $e->getMessage()
            ], 500);
        }
    }
}
