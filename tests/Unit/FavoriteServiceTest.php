<?php

namespace Tests\Unit;

use App\Models\Campus;
use App\Models\Major;
use App\Models\User;
use App\Services\FavoriteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_toggle_adds_then_removes_favorite(): void
    {
        $service = new FavoriteService;
        $user = User::factory()->create();
        $campus = Campus::factory()->create();

        $this->assertTrue($service->toggle($user, $campus));
        $this->assertTrue($service->isFavorited($user, $campus));

        $this->assertFalse($service->toggle($user, $campus));
        $this->assertFalse($service->isFavorited($user, $campus));
    }

    public function test_favorites_are_scoped_per_user(): void
    {
        $service = new FavoriteService;
        $campus = Campus::factory()->create();
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $service->toggle($owner, $campus);

        $this->assertFalse($service->isFavorited($other, $campus));
    }

    public function test_list_returns_mixed_item_types(): void
    {
        $service = new FavoriteService;
        $user = User::factory()->create();
        $campus = Campus::factory()->create();
        $major = Major::factory()->create();

        $service->toggle($user, $campus);
        $service->toggle($user, $major);

        $items = $service->listFor($user)->pluck('favoritable');

        $this->assertCount(2, $items);
        $this->assertTrue($items->contains(fn ($item) => $item instanceof Campus));
        $this->assertTrue($items->contains(fn ($item) => $item instanceof Major));
    }
}
