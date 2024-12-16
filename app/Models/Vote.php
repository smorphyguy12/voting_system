<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'student_id', 
        'candidate_id', 
        'election_id', 
        'is_verified'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }
}