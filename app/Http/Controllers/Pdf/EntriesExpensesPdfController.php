<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Offering;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Collection as SupportCollection;
use Inertia\Inertia;
use Inertia\Response;

final class EntriesExpensesPdfController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('reports/entries_expenses');
    }

    public function show(Request $request): HttpResponse
    {
        $startOfMonth = $request->date('startDate')?->startOfMonth();
        $endOfMonth = $request->date('endDate')?->endOfMonth();

        $offerings = Offering::query()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with(['offeringType', 'donor', 'transaction'])
            ->get()
            ->groupBy(function (Offering $offering): string {
                /** @phpstan-ignore-next-line */
                return match ($offering->offering_type_type) {
                    /** @phpstan-ignore-next-line */
                    'offering_type' => $offering->offeringType?->name ?? '',
                    /** @phpstan-ignore-next-line */
                    'missionary' => "{$offering->offeringType?->name} {$offering->offeringType?->last_name}",
                    default => '',
                };
            })
            ->map(fn (Collection $group): Collection => $group->groupBy(fn (Offering $offering): string => $offering->date->format('Y-m'))->sortKeys());

        $entries = $offerings->map(fn (Collection $group): SupportCollection => $group->map(fn (Collection $group): mixed => $group->sum('transaction.amountFloat')))->toArray();

        $expenses = Expense::query()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with(['expenseType', 'member'])
            ->get()
            ->groupBy(fn (Expense $expense): string => $expense->date->format('Y-m'))
            ->map(fn (Collection $group): Collection => $group->groupBy(fn (Expense $expense): string => $expense->expenseType->name));

        $dates = $this->getMonthsBetweenDates($startOfMonth ?? now()->startOfMonth(), $endOfMonth ?? now()->endOfMonth());

        return Pdf::loadView('pdf.entries_expenses', [
            'title' => __('Entries and Expenses - :month1 :year1 to :month2 :year2', [
                'month1' => Carbon::parse($startOfMonth)->translatedFormat('F'),
                'year1' => Carbon::parse($startOfMonth)->format('Y'),
                'month2' => Carbon::parse($endOfMonth)->translatedFormat('F'),
                'year2' => Carbon::parse($endOfMonth)->format('Y'),
            ]),
            'entries' => $entries,
            'expenses' => $expenses,
            'dates' => $dates,
        ])
            ->setPaper('letter', 'portrait')
            ->stream('entries_expenses_'.Carbon::parse($startOfMonth)->format('Y_m').'.pdf');
    }

    /**
     * Get months between two dates.
     *
     * @return array<int, string>
     */
    private function getMonthsBetweenDates(CarbonInterface $start, CarbonInterface $end): array
    {
        $months = [];
        $current = $start;

        while ($current <= $end) {
            $months[] = $current->format('Y-m');
            $current = $current->addMonth();
        }

        return $months;
    }
}
