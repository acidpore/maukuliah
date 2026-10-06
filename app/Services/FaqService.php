<?php

namespace App\Services;

use App\Models\Faq;
use Illuminate\Support\Collection;

class FaqService
{
    /**
     * FAQ khusus halaman diutamakan, bila kosong dipakai FAQ umum.
     *
     * @return Collection<int, Faq>
     */
    public function forPath(string $path): Collection
    {
        $specific = Faq::where('scope_type', Faq::SCOPE_PATH)
            ->where('scope_key', $this->normalize($path))
            ->orderBy('sort')
            ->get();

        if ($specific->isNotEmpty()) {
            return $specific;
        }

        return Faq::where('scope_type', Faq::SCOPE_GLOBAL)->orderBy('sort')->get();
    }

    private function normalize(string $path): string
    {
        return '/'.trim($path, '/');
    }
}
