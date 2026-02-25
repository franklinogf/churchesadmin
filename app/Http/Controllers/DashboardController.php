<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\ChurchWallet;
use App\Models\CurrentYear;
use App\Models\Expense;
use App\Models\Member;
use App\Models\Missionary;
use App\Models\Offering;
use App\Models\Visit;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Services\FormatterService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

use function in_array;

final class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    private const string GROUP_MONTH_FORMAT = 'M';

    private const string MONTH_FORMAT = 'MMMM';

    public function __invoke(): Response
    {
        return Inertia::render('dashboard', [
            'expenses' => $this->getExpensesData(),
            'offerings' => $this->getOfferingsData(),
            'persons' => $this->getPersonsData(),
            'wallets' => $this->getWalletsData(),
        ]);
    }

    /**
     * Get the wallets data grouped by month.
     *
     * @return array{month: string, wallet: string, deposits: string, withdrawals: string}[] The wallets data.
     */
    private function getWalletsData(): array
    {
        $wallets = ChurchWallet::query()
            ->with('transactions')
            ->get();

        $transactions = $wallets->flatMap(fn (ChurchWallet $wallet): Collection => $wallet->transactions)
            ->groupBy(function (Transaction $transaction): string {
                /** @var ChurchWallet $churchWallet */
                $churchWallet = $transaction->wallet->holder;

                return $churchWallet->name.'-'.$transaction->created_at->format(self::GROUP_MONTH_FORMAT);
            })
            ->mapWithKeys(function (Collection $group): array {
                /** @var ChurchWallet $churchWallet */
                $churchWallet = $group->first()?->wallet->holder;

                return [
                    $churchWallet->name => [
                        $group->first()?->created_at->format(self::GROUP_MONTH_FORMAT) => [
                            'deposits' => $this->formatAmount($group->sum(fn (Transaction $transaction): string => $transaction->type === TransactionType::DEPOSIT->value ? $transaction->amount : '0')),
                            'withdrawals' => $this->formatAmount($group->sum(fn (Transaction $transaction): string => $transaction->type === TransactionType::WITHDRAW->value ? $transaction->amount : '0')),
                        ],
                    ],
                ];
            });

        /**
         * @var array{month: string, wallet: string, deposits: string, withdrawals: string}[] $data
         */
        $data = $transactions->flatMap(fn (array $transaction, string $walletName): array => $this->months()
            ->map(fn (string $month): array => [
                'month' => $this->formatMonth($month),
                'wallet' => $walletName,
                'deposits' => $transaction[$month]['deposits'] ?? '0',
                'withdrawals' => $transaction[$month]['withdrawals'] ?? '0',
            ])
            ->toArray())->toArray();

        return $data;

    }

    /**
     * Format the amount to a string with two decimal places.
     */
    private function formatAmount(float|string $amount): float
    {
        $formatter = new FormatterService;

        return (float) $formatter->floatValue(abs((float) $amount), 2);
    }

    /**
     * Get the offerings data grouped by month.
     *
     * @return array{month: string, total: string}[] The offerings data.
     */
    private function getOfferingsData(): array
    {
        $offerings = Offering::query()
            ->with('transaction')
            ->get()
            ->groupBy(fn (Offering $offering) => $offering->date->format(self::GROUP_MONTH_FORMAT))
            ->map(fn (Collection $group): array => [
                'month' => $group->first()?->date->format(self::GROUP_MONTH_FORMAT),
                'total' => $this->formatAmount($group->sum(fn (Offering $offering): string => $offering->transaction->amount)),
            ]);
        /**
         * @var array{month: string, total: string}[] $data
         */
        $data = $this->months()
            ->map(fn (string $month): array => [
                'month' => $this->formatMonth($month),
                'total' => $offerings->get($month, [])['total'] ?? '0',
            ])
            ->values()
            ->toArray();

        return $data;
    }

    /**
     * Get the expenses data grouped by month.
     *
     * @return array{month: string, total: string}[] The expenses data.
     */
    private function getExpensesData(): array
    {
        $expenses = Expense::query()
            ->with('transaction')
            ->get()
            ->groupBy(fn (Expense $expense) => $expense->date->format(self::GROUP_MONTH_FORMAT))
            ->map(fn (Collection $group): array => [
                'month' => $group->first()?->date->format(self::GROUP_MONTH_FORMAT),
                'total' => $this->formatAmount($group->sum(fn (Expense $expense): string => $expense->transaction->amount)),
            ]);

        /**
         * @var array{month: string, total: string}[] $data
         */
        $data = $this->months()
            ->map(fn (string $month): array => [
                'month' => $this->formatMonth($month),
                'total' => $expenses->get($month, [])['total'] ?? '0',
            ])
            ->values()
            ->toArray();

        return $data;
    }

    /**
     * Get the persons data grouped by month.
     *
     * @return array{month: string, members: int, missionaries: int, visitors: int}[] The persons data.
     */
    private function getPersonsData(): array
    {
        $currentYear = CurrentYear::current()->year;

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

        /**
         * @var array{month: string, members: int, missionaries: int, visitors: int}[] $data
         */
        $data = $this->months()
            ->map(fn (string $month): array => [
                'month' => $this->formatMonth($month),
                'members' => $membersByMonth->get($month, collect())->count(),
                'missionaries' => $missionariesByMonth->get($month, collect())->count(),
                'visitors' => $visitsByMonth->get($month, collect())->count(),
            ])
            ->values()
            ->toArray();

        return $data;
    }

    /**
     * Get the months of the current year up to the current month.
     *
     * @return Collection<int, non-falsy-string>
     */
    private function months(): Collection
    {
        return collect(range(1, now()->month))
            ->map(fn (int $month) => CarbonImmutable::createFromDate(now()->year, $month, 1)->format(self::GROUP_MONTH_FORMAT));
    }

    /**
     * Format the month string to a more readable format.
     *
     * @param  string  $month  The month in 'M' format.
     * @return string The formatted month name.
     */
    private function formatMonth(string $month): string
    {
        $month = CarbonImmutable::createFromFormat(self::GROUP_MONTH_FORMAT, $month)?->isoFormat(self::MONTH_FORMAT);

        return in_array($month, [null, '', '0'], true) ? 'Unknown' : $month;
    }
}
