<?php

namespace App\Services;

use Illuminate\Support\Collection;

class TryoutCatalog
{
    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function all(): Collection
    {
        return collect(config('tryouts.tryouts'))
            ->map(fn (array $tryout, string $slug) => $this->present($slug, $tryout))
            ->values();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $slug): ?array
    {
        $tryout = config("tryouts.tryouts.{$slug}");

        return $tryout === null ? null : $this->present($slug, $tryout);
    }

    /**
     * Skor dihitung di server; soal yang tidak dijawab dianggap salah.
     *
     * @param  array<string, mixed>  $tryout
     * @param  array<int, int|string>  $answers
     * @return array{correct: int, total: int, percent: int, review: array<int, array<string, mixed>>}
     */
    public function score(array $tryout, array $answers): array
    {
        $review = [];
        $correct = 0;

        foreach ($tryout['questions'] as $index => $question) {
            $chosen = isset($answers[$index]) ? (int) $answers[$index] : null;
            $isCorrect = $chosen === $question['answer'];
            $correct += $isCorrect ? 1 : 0;

            $review[] = [
                'text' => $question['text'],
                'options' => $question['options'],
                'chosen' => $chosen,
                'answer' => $question['answer'],
                'is_correct' => $isCorrect,
                'explanation' => $question['explanation'],
            ];
        }

        $total = count($tryout['questions']);

        return [
            'correct' => $correct,
            'total' => $total,
            'percent' => $total === 0 ? 0 : (int) round($correct / $total * 100),
            'review' => $review,
        ];
    }

    /**
     * @param  array<string, mixed>  $tryout
     * @return array<string, mixed>
     */
    private function present(string $slug, array $tryout): array
    {
        return $tryout + [
            'slug' => $slug,
            'question_count' => count($tryout['questions']),
            'is_open' => $tryout['status'] === config('tryouts.status_open'),
        ];
    }
}
