<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Offering;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
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
            ->groupBy(fn (Offering $offering) => match ($offering->offering_type_type) {
                'offering_type' => $offering->offeringType->name,
                'missionary' => "{$offering->offeringType->name} {$offering->offeringType->last_name}",
                default => '',
            })
            ->map(fn ($group) => $group->groupBy(fn (Offering $offering) => $offering->date->format('Y-m'))->sortKeys());

        $entries = $offerings->map(fn ($group) => $group->map(fn ($group) => $group->sum('transaction.amountFloat')))->toArray();

        $expenses = Expense::query()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with(['expenseType', 'member'])
            ->get()
            ->groupBy(fn (Expense $expense) => $expense->date->format('Y-m'))
            ->map(fn ($group) => $group->groupBy(fn (Expense $expense) => $expense->expenseType->name));

        $dates = $this->getMonthsBetweenDates($startOfMonth, $endOfMonth);

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
