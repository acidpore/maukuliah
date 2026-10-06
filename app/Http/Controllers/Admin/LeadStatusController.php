<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateLeadStatusRequest;
use App\Models\Lead;
use App\Services\LeadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class LeadStatusController extends Controller
{
    public function __construct(private readonly LeadService $leads) {}

    public function update(UpdateLeadStatusRequest $request, Lead $lead): RedirectResponse
    {
        Gate::authorize('update', $lead);

        $this->leads->updateStatus($lead, $request->status());

        return back();
    }
}
