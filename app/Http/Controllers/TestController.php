<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestSubmissionRequest;
use App\Models\TestResult;
use App\Services\PotentialTestRegistry;
use App\Services\TestResultPresenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TestController extends Controller
{
    public function __construct(
        private readonly PotentialTestRegistry $registry,
        private readonly TestResultPresenter $presenter,
    ) {}

    public function index(): View
    {
        return view('tests.index', ['tests' => $this->registry->all()]);
    }

    public function start(string $type): View
    {
        $descriptor = $this->registry->descriptor($type);

        return view('tests.start', [
            'type' => $type,
            'title' => $descriptor['title'],
            'description' => $descriptor['description'],
            'questions' => $this->registry->questions($type),
            'scaleLabels' => $this->registry->scaleLabels($type),
        ]);
    }

    /**
     * Tamu boleh mengerjakan tes. Jawabannya disimpan sementara lalu diselesaikan
     * setelah masuk atau mendaftar, supaya hasil terikat ke akun.
     */
    public function submit(TestSubmissionRequest $request, string $type): RedirectResponse
    {
        if ($request->user() === null) {
            $request->session()->put(config('potential_tests.session_key'), [
                'type' => $type,
                'answers' => $request->answers(),
            ]);
            $request->session()->put('url.intended', route('tests.resume'));

            return redirect()->route('login')
                ->with('status', 'Masuk atau daftar untuk melihat hasil tesmu.');
        }

        return $this->redirectToResult(
            $this->registry->submit($type, $request->user(), $request->answers()),
        );
    }

    public function resume(Request $request): RedirectResponse
    {
        $pending = $request->session()->pull(config('potential_tests.session_key'));

        if ($pending === null) {
            return redirect()->route('tests.index');
        }

        return $this->redirectToResult(
            $this->registry->submit($pending['type'], $request->user(), $pending['answers']),
        );
    }

    public function result(TestResult $testResult): View
    {
        Gate::authorize('view', $testResult);

        $presented = $this->presenter->present($testResult);

        return view('tests.result', [
            'testResult' => $testResult,
            'summary' => $presented['summary'],
            'scores' => $presented['scores'],
            'recommendedMajors' => $presented['majors'],
        ]);
    }

    public function history(Request $request): View
    {
        $results = $request->user()
            ->testResults()
            ->latest()
            ->paginate(config('potential_tests.history_per_page'));

        return view('tests.history', [
            'results' => $results,
            'testTitles' => $this->registry->titlesByType(),
        ]);
    }

    private function redirectToResult(TestResult $testResult): RedirectResponse
    {
        return redirect()->route('tests.result', $testResult);
    }
}
