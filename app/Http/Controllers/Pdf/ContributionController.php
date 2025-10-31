<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrentYear\CurrentYearResource;
use App\Models\CurrentYear;
use App\Models\Member;
use App\Support\SelectOption;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class ContributionController extends Controller
{
    public function index(Request $request): Response
    {
        $selectedYear = $request->string('year');

        $selectedYear = $selectedYear->isEmpty()
        ? CurrentYear::previous()
        : CurrentYear::query()->where('year', $selectedYear)->first();

        if (! $selectedYear) {
            abort(404, 'Fiscal year not found.');
        }

        $members = Member::query()->with([
            'previousYearContributions' => fn (HasMany $query): HasMany => $query->where('current_year_id', $selectedYear->id),
            'previousYearContributions.transaction',
        ])->get();

        $contributions = $members->map(fn (Member $member): array => [
            'name' => sprintf('%s %s', $member->last_name, $member->name),
            'email' => $member->email,
            'contributionAmount' => format_to_currency($member->previousYearContributions->sum('transaction.amount')),
        ]);

        $years = CurrentYear::query()
            ->orderByDesc('year')
            ->get(['year']);

        return Inertia::render('reports/contributions', [
            'year' => new CurrentYearResource($selectedYear),
            'years' => SelectOption::create($years, 'year', 'year'),
            'contributions' => $contributions,
        ]);
    }

    public function show(Request $request)
    {
        // Validate request parameters if needed

        // Generate PDF logic here
        $pdfContent = 'PDF content for contributions report';

        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="contributions_report.pdf"');
    }
}
