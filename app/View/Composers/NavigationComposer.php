<?php

namespace App\View\Composers;

use App\Enums\UserRole;
use App\Services\FavoriteService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Menyediakan data navbar agar Blade tidak perlu melakukan query sendiri.
 */
class NavigationComposer
{
    private const ADMIN_ROLES = [UserRole::SuperAdmin, UserRole::CampusAdmin];

    public function __construct(private readonly FavoriteService $favorites) {}

    public function compose(View $view): void
    {
        $user = Auth::user();

        $view->with([
            'authUser' => $user,
            'favoriteCount' => $user ? $this->favorites->countFor($user) : 0,
            'isAdmin' => $user !== null && in_array($user->role, self::ADMIN_ROLES, true),
        ]);
    }
}
