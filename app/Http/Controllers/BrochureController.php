<?php

namespace App\Http\Controllers;

use App\Enums\LeadSource;
use App\Models\Brochure;
use App\Models\Campus;
use App\Services\LeadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BrochureController extends Controller
{
    public function __construct(private readonly LeadService $leads) {}

    /**
     * Unduhan hanya untuk pengguna login agar minatnya tercatat sebagai lead.
     * Lead tidak dibuat bila berkas belum tersedia.
     */
    public function download(Request $request, Campus $campus, Brochure $brochure): StreamedResponse
    {
        abort_unless($campus->isVerified(), 404);
        abort_unless(Storage::exists($brochure->file_path), 404);

        $this->leads->submit($request->user(), $campus, LeadSource::Brochure);

        return Storage::download($brochure->file_path, basename($brochure->file_path));
    }
}
