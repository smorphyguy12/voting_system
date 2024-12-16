<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Performance optimized statistics gathering
        $stats = [
            'students' => ['total' => User::whereHas('role', fn($q) => $q->where('name', 'student'))->count(), 'verified' => User::whereHas('role', fn($q) => $q->where('name', 'student'))->whereNotNull('email_verified_at')->count(),],
            'elections' => ['total' => Election::count(), 'active' => Election::where('status', 'active')->count(), 'upcoming' => Election::where('status', 'upcoming')->count(), 'closed' => Election::where('status', 'closed')->count(),],
            'candidates' => ['total' => Candidate::count(), 'by_election' => Candidate::select('election_id', DB::raw('count(*) as count'))->groupBy('election_id')->get()],
            'votes' => ['total' => Vote::where('is_verified', true)->count(), 'by_election' => Vote::select('election_id', DB::raw('count(*) as count'))->where('is_verified', true)->groupBy('election_id')->get()]
        ];

        // Voter turnout calculation
        $totalStudents = User::whereHas('role', fn($q) => $q->where('name', 'student'))->count();
        $totalVotes = Vote::where('is_verified', true)->count();
        $voterTurnout = $totalStudents > 0 ? round(($totalVotes / $totalStudents) * 100, 2) : 0;

        return view('admin.dashboard', ['stats' => $stats, 'voterTurnout' => $voterTurnout]);
    }
    public function elections()
    {
        $elections = Election::all();
        return view('admin.elections.index', compact('elections'));
    }

    public function candidates()
    {
        $candidates = Candidate::with(['user', 'election'])
            ->get();
        return view('admin.candidates.index', compact('candidates'));
    }

    public function students()
    {
        $students = User::whereHas('role', function ($query) {
            $query->where('name', 'student');
        })->get();
        return view('admin.students.index', compact('students'));
    }
}
