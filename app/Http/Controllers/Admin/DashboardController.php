<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Services\AdminStatsService;
use App\Services\LeadService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const LEADS_PER_PAGE = 15;

    public function __construct(
        private readonly LeadService $leads,
        private readonly AdminStatsService $stats,
    ) {}

    public function __invoke(Request $request): View
    {
        $user = $request->user();

        return view('admin.dashboard', [
            'leads' => $this->leads->paginateFor($user, self::LEADS_PER_PAGE),
            'statusOptions' => LeadStatus::cases(),
            'campus' => $user->campus,
            'stats' => $this->stats->forUser($user),
        ]);
    }
}
