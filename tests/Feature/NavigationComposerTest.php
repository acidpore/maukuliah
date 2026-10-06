<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Campus;
use App\Models\User;
use App\Services\FavoriteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationComposerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function layoutData(?User $user): array
    {
        if ($user) {
            $this->actingAs($user);
        }

        $view = view('layouts.app');
        app('view')->callComposer($view);

        return $view->getData();
    }

    public function test_guest_gets_empty_navigation_state(): void
    {
        $data = $this->layoutData(null);

        $this->assertNull($data['authUser']);
        $this->assertSame(0, $data['favoriteCount']);
        $this->assertFalse($data['isAdmin']);
    }

    public function test_user_gets_favorite_count_and_admin_flag(): void
    {
        $user = User::factory()->create();
        app(FavoriteService::class)->toggle($user, Campus::factory()->create());

        $data = $this->layoutData($user);

        $this->assertTrue($data['authUser']->is($user));
        $this->assertSame(1, $data['favoriteCount']);
        $this->assertFalse($data['isAdmin']);
    }

    public function test_admin_roles_are_flagged(): void
    {
        $admin = User::factory()->create(['role' => UserRole::SuperAdmin]);

        $this->assertTrue($this->layoutData($admin)['isAdmin']);
    }
}
