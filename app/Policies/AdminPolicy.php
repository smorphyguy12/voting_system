<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    public function viewDashboard(User $user)
    {
        return $user->isAdmin();
    }

    public function manageElections(User $user)
    {
        return $user->isAdmin() || $user->hasRole('election_manager');
    }

    public function manageCandidates(User $user)
    {
        return $user->isAdmin() || $user->hasRole('candidate_manager');
    }

    public function viewReports(User $user)
    {
        return $user->isAdmin() || $user->hasRole('reporter');
    }
}
