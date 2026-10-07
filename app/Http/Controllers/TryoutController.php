<?php

namespace App\Http\Controllers;

use App\Http\Requests\TryoutSubmissionRequest;
use App\Services\TryoutCatalog;
use Illuminate\View\View;

class TryoutController extends Controller
{
    public function __construct(private readonly TryoutCatalog $catalog) {}

    public function index(): View
    {
        return view('tryouts.index', ['tryouts' => $this->catalog->all()]);
    }

    public function show(string $slug): View
    {
        return view('tryouts.show', ['tryout' => $this->findOrFail($slug)]);
    }

    public function take(string $slug): View
    {
        return view('tryouts.take', ['tryout' => $this->findOpenOrFail($slug)]);
    }

    public function submit(TryoutSubmissionRequest $request, string $slug): View
    {
        $tryout = $this->findOpenOrFail($slug);

        return view('tryouts.result', [
            'tryout' => $tryout,
            'result' => $this->catalog->score($tryout, $request->answers()),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function findOrFail(string $slug): array
    {
        return $this->catalog->find($slug) ?? abort(404);
    }

    /**
     * @return array<string, mixed>
     */
    private function findOpenOrFail(string $slug): array
    {
        $tryout = $this->findOrFail($slug);
        abort_unless($tryout['is_open'], 404);

        return $tryout;
    }
}
