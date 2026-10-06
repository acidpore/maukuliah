<?php

namespace App\Policies;

use App\Models\TestResult;
use App\Models\User;

class TestResultPolicy
{
    public function view(User $user, TestResult $testResult): bool
    {
        return $user->getKey() === $testResult->user_id;
    }
}
