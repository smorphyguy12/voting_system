<?php

namespace App\Services;

use App\Models\Election;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class ElectionAnalyticsService
{
    public function getElectionResults(Election $election)
    {
        // Detailed election results with vote breakdown
        $results = Vote::select(
            'candidates.id as candidate_id', 
            'users.full_name', 
            'candidates.candidate_role',
            DB::raw('COUNT(votes.id) as vote_count')
        )
        ->join('candidates', 'votes.candidate_id', '=', 'candidates.id')
        ->join('users', 'candidates.user_id', '=', 'users.id')
        ->where('votes.election_id', $election->id)
        ->where('votes.is_verified', true)
        ->groupBy('candidates.id', 'users.full_name', 'candidates.candidate_role')
        ->orderBy('vote_count', 'desc')
        ->get();

        // Calculate total verified votes
        $totalVotes = $results->sum('vote_count');

        // Calculate vote percentages
        $results = $results->map(function ($candidate) use ($totalVotes) {
            $candidate->vote_percentage = $totalVotes > 0 
                ? round(($candidate->vote_count / $totalVotes) * 100, 2) 
                : 0;
            return $candidate;
        });

        return [
            'results' => $results,
            'total_votes' => $totalVotes
        ];
    }

    public function generateElectionReport(Election $election)
    {
        $results = $this->getElectionResults($election);
        
        // Generate a PDF report
        $pdf = PDF::loadView('reports.election_results', [
            'election' => $election,
            'results' => $results['results'],
            'total_votes' => $results['total_votes']
        ]);

        return $pdf->download("election_results_{$election->name}.pdf");
    }
}