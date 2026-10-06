<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Campus;
use App\Models\User;

class CampusPolicy
{
    public function update(User $user, Campus $campus): bool
    {
        return $user->hasRole(UserRole::SuperAdmin) || $this->managesCampus($user, $campus);
    }

    public function submitForVerification(User $user, Campus $campus): bool
    {
        return $this->managesCampus($user, $campus);
    }

    public function verify(User $user): bool
    {
        return $user->hasRole(UserRole::SuperAdmin);
    }

    private function managesCampus(User $user, Campus $campus): bool
    {
        return $user->hasRole(UserRole::CampusAdmin)
            && $user->campus_id === $campus->getKey();
    }
}
