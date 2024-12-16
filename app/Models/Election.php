<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Election extends Model
{
    protected $fillable = [
        'name', 
        'start_date', 
        'end_date', 
        'status'
    ];

    protected $dates = [
        'start_date', 
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    // Relationships
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    // Mutators and Accessors
    public function getStatusColorAttribute()
    {
        return [
            'upcoming' => 'warning',
            'active' => 'success',
            'closed' => 'danger'
        ][$this->status] ?? 'secondary';
    }

    // Automatic status update
    public function updateStatus()
    {
        $now = Carbon::now();
        
        if ($now < $this->start_date) {
            $this->status = 'upcoming';
        } elseif ($now >= $this->start_date && $now <= $this->end_date) {
            $this->status = 'active';
        } else {
            $this->status = 'closed';
        }

        $this->save();
    }

    // Calculate election results
    public function calculateResults()
    {
        return $this->candidates
            ->map(function ($candidate) {
                $voteCount = $candidate->votes()
                    ->where('is_verified', true)
                    ->count();
                
                return [
                    'candidate' => $candidate->user->full_name,
                    'votes' => $voteCount,
                    'percentage' => $this->calculateVotePercentage($voteCount)
                ];
            })
            ->sortByDesc('votes');
    }

    private function calculateVotePercentage($candidateVotes)
    {
        $totalVotes = $this->votes()
            ->where('is_verified', true)
            ->count();

        return $totalVotes > 0 
            ? round(($candidateVotes / $totalVotes) * 100, 2)
            : 0;
    }
}