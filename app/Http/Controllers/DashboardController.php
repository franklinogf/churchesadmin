<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Member;
use App\Models\Missionary;
use App\Models\Offering;
use App\Models\Visit;
use Bavix\Wallet\Services\FormatterService;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    private const string GROUP_MONTH_FORMAT = 'M';

    private const string MONTH_FORMAT = 'MMMM';

    public function __invoke(Request $request): Response
    {
        return Inertia::render('dashboard', [
            'expenses' => $this->getExpensesData(),
            'offerings' => $this->getOfferingsData(),
            'persons' => $this->getPersonsData(),
        ]);
    }

    private function formatAmount(float|string $amount): string
    {
        $formatter = new FormatterService;

        return $formatter->floatValue(abs($amount), 2);
    }

    private function getOfferingsData(): array
    {
        $offerings = Offering::query()
            ->with('transaction')
            ->get()
            ->groupBy(fn (Offering $offering) => $offering->date->format(self::GROUP_MONTH_FORMAT))
            ->map(fn (Collection $group) => [
                'month' => $group->first()?->date->format(self::MONTH_FORMAT),
                'total' => $this->formatAmount($group->sum(fn (Offering $offering): string => $offering->transaction->amount)),
            ]);

        $months = $this->months();

        return collect($months)
            ->map(function (string $month) use ($offerings): array {
                return [
                    'month' => $this->formatMonth($month),
                    'total' => $offerings->get($month, [])['total'] ?? 0,
                ];
            })
            ->values()
            ->toArray();
    }

    private function getExpensesData(): array
    {
        $expenses = Expense::query()
            ->with('transaction')
            ->get()
            ->groupBy(fn (Expense $expense) => $expense->date->format(self::GROUP_MONTH_FORMAT))
            ->map(fn (Collection $group) => [
                'month' => $group->first()->date->format(self::MONTH_FORMAT),
                'total' => $this->formatAmount($group->sum(fn (Expense $expense): string => $expense->transaction->amount)),
            ]);

        $months = $this->months();

        return collect($months)
            ->map(function (string $month) use ($expenses): array {
                return [
                    'month' => $this->formatMonth($month),
                    'total' => $expenses->get($month, [])['total'] ?? 0,
                ];
            })
            ->values()
            ->toArray();
    }

    private function getPersonsData(): array
    {
        $currentYear = now()->year;

        // Get members created by month
        $membersByMonth = Member::query()
            ->whereYear('created_at', $currentYear)
            ->get()
            ->groupBy(fn (Member $member) => $member->created_at->format(self::GROUP_MONTH_FORMAT));

        // Get missionaries created by month
        $missionariesByMonth = Missionary::query()
            ->whereYear('created_at', $currentYear)
            ->get()
            ->groupBy(fn (Missionary $missionary) => $missionary->created_at->format(self::GROUP_MONTH_FORMAT));

        // Get visits created by month
        $visitsByMonth = Visit::query()
            ->whereYear('created_at', $currentYear)
            ->get()
            ->groupBy(fn (Visit $visit) => $visit->created_at->format(self::GROUP_MONTH_FORMAT));

        // Create the months array (all months until now for current year)
        $months = $this->months();

        // Build the persons array with data for all months
        return collect($months)
            ->map(function (string $month) use ($membersByMonth, $missionariesByMonth, $visitsByMonth): array {
                return [
                    'month' => $this->formatMonth($month),
                    'members' => $membersByMonth->get($month, collect())->count(),
                    'missionaries' => $missionariesByMonth->get($month, collect())->count(),
                    'visitors' => $visitsByMonth->get($month, collect())->count(),
                ];
            })
            ->values()
            ->toArray();
    }

    private function months(): array
    {
        return collect(range(1, now()->month))
            ->map(fn (int $month) => CarbonImmutable::createFromDate(now()->year, $month, 1)->format(self::GROUP_MONTH_FORMAT))
            ->toArray();
    }

    private function formatMonth(string $month): string
    {
        return ucfirst(CarbonImmutable::createFromFormat(self::GROUP_MONTH_FORMAT, $month)->isoFormat(self::MONTH_FORMAT));
    }
}
