<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    public function update(User $user, Lead $lead): bool
    {
        if ($user->hasRole(UserRole::SuperAdmin)) {
            return true;
        }

        return $user->hasRole(UserRole::CampusAdmin)
            && $user->campus_id === $lead->campus_id;
    }
}
