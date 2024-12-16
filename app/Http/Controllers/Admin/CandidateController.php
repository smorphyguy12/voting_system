<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::with(['user', 'election'])->get();
        return view('admin.candidates.index', compact('candidates'));
    }

    public function create()
    {
        $elections = Election::where('status', '!=', 'closed')->get();
        $students = User::whereDoesntHave('candidates', function($query) {
            $query->whereHas('election', function($subQuery) {
                $subQuery->where('status', '!=', 'closed');
            });
        })->get();

        return view('admin.candidates.create', [
            'elections' => $elections,
            'students' => $students
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'election_id' => 'required|exists:elections,id',
            'candidate_role' => 'required|in:President,Vice President,Secretary,Treasurer',
            'platform' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle profile picture upload
        $profilePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePath = $request->file('profile_picture')->store('candidate_profiles', 'public');
        }

        $candidate = Candidate::create([
            'user_id' => $request->user_id,
            'election_id' => $request->election_id,
            'candidate_role' => $request->candidate_role,
            'platform' => $request->platform,
            'profile_picture' => $profilePath
        ]);
        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate created successfully');
    }

    public function show(Candidate $candidate)
    {
        return view('admin.candidates.show', compact('candidate'));
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

    public function update(Request $request, Candidate $candidate)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'election_id' => 'required|exists:elections,id',
            'candidate_role' => 'required|in:President,Vice President,Secretary,Treasurer',
            'platform' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($candidate->profile_picture) {
                Storage::disk('public')->delete($candidate->profile_picture);
            }
            $profilePath = $request->file('profile_picture')->store('candidate_profiles', 'public');
        } else {
            $profilePath = $candidate->profile_picture;
        }

        $candidate->update([
            'user_id' => $request->user_id,
            'election_id' => $request->election_id,
            'candidate_role' => $request->candidate_role,
            'platform' => $request->platform,
            'profile_picture' => $profilePath
        ]);

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate updated successfully');
    }

    public function destroy(Candidate $candidate)
    {
        // Delete profile picture if exists
        if ($candidate->profile_picture) {
            Storage::disk('public')->delete($candidate->profile_picture);
        }

        $candidate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Candidate deleted successfully'
        ]);
    }
}