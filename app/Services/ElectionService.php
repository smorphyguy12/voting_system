<?php 
namespace App\Services;

use App\Models\Election;
use App\Models\Candidate;
use App\Notifications\ElectionAnnouncementNotification;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Vote;

class ElectionService
{
    public function createElection(array $data)
    {
        return DB::transaction(function () use ($data) {
            $election = Election::create([
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'description' => $data['description'] ?? null
            ]);

            // Log election creation
            app(AuditLogService::class)->log(
                'create_election', 
                "Election {$election->name} created",
                $election
            );

            return $election;
        });
    }

    public function announceElection(Election $election)
    {
        // Notify eligible students
        $eligibleStudents = User::whereHas('role', function($query) {
            $query->where('name', 'student');
        })->get();

        $eligibleStudents->each(function($student) use ($election) {
            $student->notify(new ElectionAnnouncementNotification($election));
        });
    }

    public function calculateElectionResults(Election $election)
    {
        return Candidate::where('election_id', $election->id)
            ->withCount('verifiedVotes')
            ->orderBy('verified_votes_count', 'desc')
            ->get()
            ->map(function($candidate) {
                return [
                    'candidate_name' => $candidate->user->full_name,
                    'votes' => $candidate->verified_votes_count,
                    'vote_percentage' => $this->calculateVotePercentage($candidate, $election)
                ];
            });
    }

    private function calculateVotePercentage(Candidate $candidate, Election $election)
    {
        $totalVotes = Vote::where('election_id', $election->id)
            ->where('is_verified', true)
            ->count();

        return $totalVotes > 0 
            ? round(($candidate->verifiedVotes()->count() / $totalVotes) * 100, 2)
            : 0;
    }
}
