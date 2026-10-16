<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CampusRequest;
use App\Models\Campus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CampusController extends Controller
{
    private const CAMPUSES_PER_PAGE = 20;

    public function index(): View
    {
        return view('admin.campus.index', [
            'campuses' => Campus::orderBy('name')->paginate(self::CAMPUSES_PER_PAGE),
        ]);
    }

    public function create(): View
    {
        return view('admin.campus.form', ['campus' => null]);
    }

    public function store(CampusRequest $request): RedirectResponse
    {
        $campus = Campus::create([
            ...$request->validated(),
            'slug' => $this->uniqueSlug($request->validated('name')),
            'verification_status' => VerificationStatus::Draft,
        ]);

        return redirect()
            ->route('admin.campus.edit', $campus)
            ->with('status', 'Kampus ditambahkan.');
    }

    public function edit(Campus $campus): View
    {
        return view('admin.campus.form', ['campus' => $campus]);
    }

    public function update(CampusRequest $request, Campus $campus): RedirectResponse
    {
        $campus->update($request->validated());

        return redirect()
            ->route('admin.campus.edit', $campus)
            ->with('status', 'Data kampus diperbarui.');
    }

    public function destroy(Campus $campus): RedirectResponse
    {
        $campus->delete();

        return redirect()->route('admin.campus.index')->with('status', 'Kampus dihapus.');
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $suffix = 2;

        while (DB::table('campuses')->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
