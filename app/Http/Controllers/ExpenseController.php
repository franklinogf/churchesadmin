<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FlashMessageKey;
use App\Http\Requests\Expense\StoreExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use App\Http\Resources\Expense\ExpenseResource;
use App\Models\Church;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Member;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

final class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $expenses = Expense::latest('date')->get();

        return Inertia::render('expenses/index', [
            'expenses' => ExpenseResource::collection($expenses),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $wallets = Church::current()?->wallets()->get()->map(fn ($wallet): array => [
            'value' => $wallet->id,
            'label' => $wallet->name,
        ])->toArray();

        $members = Member::all()->map(fn ($member): array => [
            'value' => $member->id,
            'label' => "{$member->name} {$member->last_name}",
        ])->toArray();

        $expenseTypes = ExpenseType::all()->map(fn ($expenseType): array => [
            'value' => $expenseType->id,
            'label' => $expenseType->name,
        ])->toArray();

        return Inertia::render('expenses/create', [
            'members' => $members,
            'wallets' => $wallets,
            'expenseTypes' => $expenseTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request): RedirectResponse
    {
        /**
         * @var array{expenses:array{date:string,wallet_id:string,member_id:string|null,expense_type_id:string,amount:float,note:string|null}[]} $validated
         */
        $validated = $request->validated();

        DB::transaction(function () use ($validated): void {
            foreach ($validated['expenses'] as $expense) {
                $wallet = Wallet::find($expense['wallet_id']);

                $transaction = $wallet?->forceWithdrawFloat(
                    $expense['amount']
                );

                Expense::create([
                    'date' => Carbon::parse($expense['date'])->setTimeFrom(now()),
                    'transaction_id' => $transaction?->id,
                    'member_id' => $expense['member_id'],
                    'expense_type_id' => $expense['expense_type_id'],
                    'note' => $expense['note'],
                ]);
            }
        });

        return to_route('expenses.index')->with(FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['resource' => __('Expense')]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense): void
    {
        //
    }
}
