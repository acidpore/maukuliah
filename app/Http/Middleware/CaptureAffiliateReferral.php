<?php

namespace App\Http\Middleware;

use App\Services\AffiliateService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CaptureAffiliateReferral
{
    public function __construct(private readonly AffiliateService $affiliates) {}

    /**
     * Menyimpan kode rujukan dari query agar tetap terbaca saat pengunjung
     * mendaftar di kunjungan berikutnya.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $code = $request->query(config('affiliate.referral_query_key'));

        if (is_string($code) && $this->affiliates->findByCode($code) !== null) {
            Cookie::queue(
                config('affiliate.cookie.name'),
                strtoupper($code),
                config('affiliate.cookie.lifetime_minutes'),
            );
        }

        return $next($request);
    }
}
