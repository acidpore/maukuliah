<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Services\CampusVerificationService;
use Closure;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CampusVerificationController extends Controller
{
    private const CAMPUSES_PER_PAGE = 15;

    public function __construct(private readonly CampusVerificationService $verification) {}

    public function index(): View
    {
        Gate::authorize('verify', Campus::class);

        $campuses = Campus::query()
            ->where('verification_status', VerificationStatus::Pending)
            ->orderBy('name')
            ->paginate(self::CAMPUSES_PER_PAGE);

        return view('admin.campuses.index', compact('campuses'));
    }

    public function submit(Request $request, Campus $campus): RedirectResponse
    {
        return $this->transition(fn () => $this->verification->submit($campus, $request->user()));
    }

    public function approve(Request $request, Campus $campus): RedirectResponse
    {
        return $this->transition(fn () => $this->verification->approve($campus, $request->user()));
    }

    public function reject(Request $request, Campus $campus): RedirectResponse
    {
        return $this->transition(fn () => $this->verification->reject($campus, $request->user()));
    }

    /**
     * Transisi yang tidak diizinkan dikembalikan sebagai pesan, bukan error server.
     */
    private function transition(Closure $action): RedirectResponse
    {
        try {
            $action();
        } catch (DomainException $exception) {
            return back()->withErrors(['status' => $exception->getMessage()]);
        }

        return back();
    }
}
