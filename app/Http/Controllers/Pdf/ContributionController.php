<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrentYear\CurrentYearResource;
use App\Models\CurrentYear;
use App\Models\Member;
use App\Support\SelectOption;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class ContributionController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $selectedYear = $request->string('year');

        $selectedYear = $selectedYear->isEmpty()
        ? CurrentYear::previous()
        : CurrentYear::query()->where('year', $selectedYear)->first();

        $members = Member::query()->get();

        $contributions = ! $selectedYear ? [] : $members->map(fn (Member $member): array => [
            'id' => $member->id,
            'name' => "$member->last_name $member->name",
            'email' => $member->email,
            'contributionAmount' => format_to_currency($member->getPreviousYearContributionsAmount($selectedYear->year)),
        ])->filter(fn (array $data): bool => $data['contributionAmount'] !== format_to_currency(0))
            ->sortBy('name')
            ->toArray();

        $years = CurrentYear::query()
            ->where('is_current', false)
            ->orderByDesc('year')
            ->get(['year']);

        return Inertia::render('reports/contributions', [
            'year' => ! $selectedYear ? null : new CurrentYearResource($selectedYear),
            'years' => $years->isEmpty() ? [] : SelectOption::create($years, 'year', 'year'),
            'contributions' => $contributions,
        ]);
    }
}
