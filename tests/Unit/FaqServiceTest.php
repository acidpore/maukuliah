<?php

namespace Tests\Unit;

use App\Models\Faq;
use App\Services\FaqService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqServiceTest extends TestCase
{
    use RefreshDatabase;

    private function faq(string $type, ?string $key, string $question, int $sort = 0): void
    {
        Faq::create([
            'scope_type' => $type,
            'scope_key' => $key,
            'question' => $question,
            'answer' => 'Jawaban',
            'sort' => $sort,
        ]);
    }

    public function test_returns_path_specific_faqs_in_order(): void
    {
        $this->faq(Faq::SCOPE_GLOBAL, null, 'Umum');
        $this->faq(Faq::SCOPE_PATH, '/jadwal-kuliah/malam', 'Kedua', 2);
        $this->faq(Faq::SCOPE_PATH, '/jadwal-kuliah/malam', 'Pertama', 1);

        $result = (new FaqService)->forPath('jadwal-kuliah/malam/');

        $this->assertSame(['Pertama', 'Kedua'], $result->pluck('question')->all());
    }

    public function test_falls_back_to_global_faqs(): void
    {
        $this->faq(Faq::SCOPE_GLOBAL, null, 'Umum');

        $result = (new FaqService)->forPath('/halaman/lain');

        $this->assertSame(['Umum'], $result->pluck('question')->all());
    }
}
