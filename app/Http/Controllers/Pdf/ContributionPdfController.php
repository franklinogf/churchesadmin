<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\CurrentYear;
use App\Models\Member;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ContributionPdfController extends Controller
{
    public function single(Request $request, Member $member): Response
    {
        /**
         * @var array{year:string} $validated
         */
        $validated = $request->validate([
            'year' => ['required', 'integer', 'exists:current_years,year'],
        ]);

        $year = CurrentYear::query()->ofYear($validated['year'])->firstOrFail();

        $contribution = $this->getContributionsForMember($member, $year);

        $title = __('Contributions Report for year :year', ['year' => $year->year]);

        return Pdf::loadView('pdf.contribution', [
            'title' => $title,
            'contribution' => $contribution,
            'year' => $year->year,
        ])
            ->setPaper('letter', 'portrait')
            ->stream('contributions_'.$year->year.'.pdf');
    }

    public function multiple(Request $request): Response
    {
        /**
         * @var array{year:string,members:int[]} $validated
         */
        $validated = $request->validate([
            'year' => ['required', 'integer', 'exists:current_years,year'],
            'members' => ['required', 'array'],
            'members.*' => ['integer', 'exists:members,id'],
        ]);

        $year = CurrentYear::query()->ofYear($validated['year'])->firstOrFail();
        $members = Member::query()
            ->whereIn('id', $validated['members'])
            ->get();

        $contributions = $members->map(fn (Member $member): array => $this->getContributionsForMember($member, $year));

        $title = __('Contributions Report for year :year', ['year' => $year->year]);

        return Pdf::loadView('pdf.contributions', [
            'title' => $title,
            'contributions' => $contributions,
            'year' => $year->year,
        ])
            ->setPaper('letter', 'portrait')
            ->stream('contributions_'.$year->year.'.pdf');
    }

    private function getContributionsForMember(Member $member, CurrentYear $year): array
    {
        return [
            'name' => "$member->last_name $member->name",
            'contributions' => $member->getPreviousYearContributions($year),
            'contributionAmount' => format_to_currency($member->getPreviousYearContributionsAmount($year)),
        ];
    }
}
