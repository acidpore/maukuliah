<?php

namespace App\Services;

use App\Models\TestResult;
use App\Models\User;
use InvalidArgumentException;

class LearningStyleService
{
    /**
     * @return array<int, array{style: string, text: string}>
     */
    public function questions(): array
    {
        return config('learning_style.questions');
    }

    /**
     * @param  array<int, int>  $answers  Jawaban Likert, kunci = indeks soal.
     * @return array{scores: array<string, int>, dominant: string, label: string, tips: string}
     */
    public function score(array $answers): array
    {
        $this->assertValid($answers);

        $scores = array_fill_keys(array_keys(config('learning_style.styles')), 0);

        foreach ($this->questions() as $index => $question) {
            $scores[$question['style']] += (int) $answers[$index];
        }

        $dominant = $this->dominantStyle($scores);
        $style = config("learning_style.styles.{$dominant}");

        return [
            'scores' => $scores,
            'dominant' => $dominant,
            'label' => $style['label'],
            'tips' => $style['tips'],
        ];
    }

    /**
     * @param  array<int, int>  $answers
     */
    public function submit(User $user, array $answers): TestResult
    {
        return $user->testResults()->create([
            'test_type' => TestResult::TYPE_LEARNING_STYLE,
            'result' => $this->score($answers),
        ]);
    }

    /**
     * @param  array<string, int>  $scores
     */
    private function dominantStyle(array $scores): string
    {
        // Urutan gaya di config menjadi pemutus seri agar hasil deterministik.
        $best = null;

        foreach ($scores as $style => $score) {
            if ($best === null || $score > $scores[$best]) {
                $best = $style;
            }
        }

        return $best;
    }

    /**
     * @param  array<int, int>  $answers
     */
    private function assertValid(array $answers): void
    {
        $min = config('learning_style.scale.min');
        $max = config('learning_style.scale.max');

        foreach (array_keys($this->questions()) as $index) {
            $value = $answers[$index] ?? null;

            if (! is_numeric($value) || $value < $min || $value > $max) {
                throw new InvalidArgumentException("Jawaban soal {$index} tidak valid.");
            }
        }
    }
}
