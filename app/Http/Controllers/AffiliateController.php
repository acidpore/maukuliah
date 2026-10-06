<?php

namespace App\Http\Controllers;

use App\Enums\AffiliateCategory;
use App\Http\Requests\RegisterAffiliateRequest;
use App\Services\AffiliateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AffiliateController extends Controller
{
    private const REFERRALS_PER_PAGE = 15;

    public function __construct(private readonly AffiliateService $affiliates) {}

    public function index(Request $request): View
    {
        return view('affiliate.index', [
            'categories' => AffiliateCategory::cases(),
            'commissionPerStudent' => config('affiliate.commission_per_student'),
            'paymentWindowDays' => config('affiliate.payment_window_days'),
            'affiliate' => $request->user()?->affiliate,
        ]);
    }

    public function register(RegisterAffiliateRequest $request): RedirectResponse
    {
        $this->affiliates->register($request->user(), $request->category());

        return redirect()->route('affiliate.dashboard');
    }

    public function dashboard(Request $request): View|RedirectResponse
    {
        $affiliate = $request->user()->affiliate;

        if ($affiliate === null) {
            return redirect()->route('affiliate.index');
        }

        return view('affiliate.dashboard', [
            'affiliate' => $affiliate,
            'referralUrl' => route('campuses.index', [config('affiliate.referral_query_key') => $affiliate->code]),
            'stats' => $this->affiliates->stats($affiliate),
            'referrals' => $affiliate->referrals()
                ->with(['application', 'commission'])
                ->latest()
                ->paginate(self::REFERRALS_PER_PAGE),
        ]);
    }
}
