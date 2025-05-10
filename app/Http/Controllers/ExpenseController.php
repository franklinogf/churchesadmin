<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Expense\CreateExpenseAction;
use App\Actions\Expense\DeleteExpenseAction;
use App\Actions\Expense\UpdateExpenseAction;
use App\Enums\FlashMessageKey;
use App\Exceptions\WalletException;
use App\Http\Requests\Expense\StoreExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use App\Http\Resources\Codes\ExpenseTypeResource;
use App\Http\Resources\Expense\ExpenseResource;
use App\Http\Resources\Wallet\ChurchWalletResource;
use App\Models\ChurchWallet;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Member;
use App\Support\SelectOption;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        $expenses = Expense::latest('date')->with([
            'transaction.wallet' => function (Relation $query): void {
                /** @phpstan-ignore-next-line */
                $query->withTrashed();
            },
        ])->get();

        return Inertia::render('expenses/index', [
            'expenses' => ExpenseResource::collection($expenses),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $wallets = ChurchWallet::all();

        $walletOptions = SelectOption::create($wallets);

        $memberOptions = SelectOption::create(Member::all(), labels: ['name', 'last_name']);

        $expenseTypes = ExpenseType::all();
        $expenseTypesOptions = SelectOption::create($expenseTypes);

        return Inertia::render('expenses/create', [
            'memberOptions' => $memberOptions,
            'wallets' => ChurchWalletResource::collection($wallets),
            'walletOptions' => $walletOptions,
            'expenseTypes' => ExpenseTypeResource::collection($expenseTypes),
            'expenseTypesOptions' => $expenseTypesOptions,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request, CreateExpenseAction $action): RedirectResponse
    {
        /**
         * @var array{expenses:array{date:string,wallet_id:string,member_id:string|null,expense_type_id:string,amount:string,note:string|null}[]} $validated
         */
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $action): void {

                foreach ($validated['expenses'] as $expense) {

                    $action->handle($expense);
                }

            });
        } catch (WalletException $e) {
            return back()->with(
                FlashMessageKey::ERROR->value,
                $e->getMessage()
            );
        }

        return to_route('expenses.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Expense')])
        );
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
    public function edit(Expense $expense): Response
    {

        $wallets = ChurchWallet::all();

        $walletOptions = SelectOption::create($wallets);

        $memberOptions = SelectOption::create(Member::all(), labels: ['name', 'last_name']);

        $expenseTypesOptions = SelectOption::create(ExpenseType::all());

        return Inertia::render('expenses/edit', [
            'expense' => new ExpenseResource($expense),
            'memberOptions' => $memberOptions,
            'wallets' => ChurchWalletResource::collection($wallets),
            'expenseTypesOptions' => $expenseTypesOptions,
            'walletOptions' => $walletOptions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense, UpdateExpenseAction $action): RedirectResponse
    {

        /**
         * @var array{date:string,wallet_id:string,member_id:string|null,expense_type_id:string,amount:float,note:string|null} $validated
         */
        $validated = $request->validated();

        try {

            $action->handle($expense, $validated);

        } catch (WalletException $e) {

            return back()->with(FlashMessageKey::ERROR->value, $e->getMessage())
                ->withErrors([
                    'wallet_id' => $e->getMessage(),
                ]);
        }

        return to_route('expenses.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Expense')])
        );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense, DeleteExpenseAction $action): RedirectResponse
    {

        try {
            $action->handle($expense);
        } catch (WalletException $e) {
            return back()
                ->with(FlashMessageKey::ERROR->value, $e->getMessage());
        }

        return to_route('expenses.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Expense')])
        );
    }
}
