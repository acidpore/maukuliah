<?php

namespace App\Services;

use App\Models\Campus;
use App\Models\Career;
use App\Models\Favorite;
use App\Models\Major;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class FavoriteService
{
    /**
     * Kunci jenis item pada formulir dan hasil daftar favorit.
     */
    private const MODELS = [
        'campus' => Campus::class,
        'major' => Major::class,
        'career' => Career::class,
        'scholarship' => Scholarship::class,
    ];

    private const GROUP_KEYS = [
        'campus' => 'campuses',
        'major' => 'majors',
        'career' => 'careers',
        'scholarship' => 'scholarships',
    ];

    /**
     * @return array<int, string>
     */
    public static function types(): array
    {
        return array_keys(self::MODELS);
    }

    /**
     * Kampus yang belum terverifikasi dianggap tidak ada agar tidak bisa difavoritkan.
     */
    public function resolve(string $type, int $id): Model
    {
        $query = self::MODELS[$type]::query();

        if ($type === 'campus') {
            $query->verified();
        }

        return $query->findOrFail($id);
    }

    /**
     * Mengembalikan true bila item menjadi favorit, false bila dilepas.
     */
    public function toggle(User $user, Model $item): bool
    {
        $existing = $this->query($user, $item)->first();

        if ($existing) {
            $existing->delete();

            return false;
        }

        $user->favorites()->create([
            'favoritable_type' => $item->getMorphClass(),
            'favoritable_id' => $item->getKey(),
        ]);

        return true;
    }

    public function isFavorited(User $user, Model $item): bool
    {
        return $this->query($user, $item)->exists();
    }

    public function isFavoritedBy(?User $user, Model $item): bool
    {
        return $user !== null && $this->isFavorited($user, $item);
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function listFor(User $user): Collection
    {
        return $user->favorites()->with('favoritable')->latest()->get();
    }

    /**
     * @return array{campuses: \Illuminate\Support\Collection, majors: \Illuminate\Support\Collection, careers: \Illuminate\Support\Collection, scholarships: \Illuminate\Support\Collection}
     */
    public function groupedFor(User $user): array
    {
        $items = $this->listFor($user)->pluck('favoritable')->filter();
        $groups = array_fill_keys(array_values(self::GROUP_KEYS), collect());

        foreach (self::MODELS as $type => $class) {
            $groups[self::GROUP_KEYS[$type]] = $items
                ->filter(fn (Model $item) => $item instanceof $class)
                ->values();
        }

        // Jumlah relasi dihitung di sini agar Blade tidak melakukan query.
        $groups['campuses'] = (new Collection($groups['campuses']->all()))->loadCount('majors');
        $groups['majors'] = (new Collection($groups['majors']->all()))->loadCount('campuses');

        return $groups;
    }

    public function countFor(User $user): int
    {
        return $user->favorites()->count();
    }

    private function query(User $user, Model $item): Builder
    {
        return Favorite::query()
            ->where('user_id', $user->getKey())
            ->where('favoritable_type', $item->getMorphClass())
            ->where('favoritable_id', $item->getKey());
    }
}
