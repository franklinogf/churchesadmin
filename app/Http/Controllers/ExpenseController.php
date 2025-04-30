<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FlashMessageKey;
use App\Helpers\SelectOption;
use App\Http\Requests\Expense\StoreExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use App\Http\Resources\Expense\ExpenseResource;
use App\Http\Resources\Wallet\WalletResource;
use App\Models\Church;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Member;
use App\Models\Wallet;
use Bavix\Wallet\Exceptions\InsufficientFunds;
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
        $expenses = Expense::latest('date')->with(['transaction.wallet' => function ($query): void {
            $query->withTrashed();
        }])->get();

        return Inertia::render('expenses/index', [
            'expenses' => ExpenseResource::collection($expenses),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $wallets = Church::current()?->wallets()->get();

        $members = SelectOption::create(Member::all(), labels: ['name', 'last_name']);

        $expenseTypes = SelectOption::create(ExpenseType::all());

        return Inertia::render('expenses/create', [
            'members' => $members,
            'wallets' => WalletResource::collection($wallets),
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

        DB::beginTransaction();
        try {
            foreach ($validated['expenses'] as $expense) {
                $wallet = Wallet::find($expense['wallet_id']);

                $transaction = $wallet?->withdrawFloat(
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
            DB::commit();
        } catch (InsufficientFunds) {
            DB::rollBack();

            return back()->with(FlashMessageKey::ERROR->value,
                __('flash.message.insufficient_funds', ['wallet' => $wallet?->name]));
        }

        return to_route('expenses.index')->with(FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Expense')]));
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
