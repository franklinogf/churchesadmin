<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Check\CreateCheckAction;
use App\Actions\Check\DeleteCheckAction;
use App\Actions\Check\UpdateCheckAction;
use App\Enums\CheckType;
use App\Enums\FlashMessageKey;
use App\Exceptions\WalletException;
use App\Http\Requests\Check\StoreCheckRequest;
use App\Http\Requests\Check\UpdateCheckRequest;
use App\Http\Resources\Check\CheckResource;
use App\Models\Check;
use App\Models\ChurchWallet;
use App\Models\ExpenseType;
use App\Models\Member;
use App\Support\SelectOption;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class CheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $unconfirmedChecks = Check::latest()->get();
        $confirmedChecks = Check::latest()->get();

        return Inertia::render('checks/index', [
            'unconfirmedChecks' => CheckResource::collection($unconfirmedChecks),
            'confirmedChecks' => CheckResource::collection($confirmedChecks),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $walletOptions = SelectOption::create(ChurchWallet::all());
        $memberOptions = SelectOption::create(Member::all(), labels: ['name', 'last_name']);
        $checkTypesOptions = CheckType::options();
        $expenseTypesOptions = SelectOption::create(ExpenseType::all());

        return Inertia::render('checks/create', [
            'walletOptions' => $walletOptions,
            'memberOptions' => $memberOptions,
            'checkTypesOptions' => $checkTypesOptions,
            'expenseTypesOptions' => $expenseTypesOptions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCheckRequest $request, CreateCheckAction $action): RedirectResponse
    {
        /**
         * @var array{amount:string,member_id:string,date:string,type:string,wallet_id:string,note:string|null,expense_type_id:string} $validated
         */
        $validated = $request->validated();
        try {
            $action->handle($validated);

        } catch (WalletException $e) {
            return back()->with(
                FlashMessageKey::ERROR->value,
                $e->getMessage()
            )->withErrors([
                'amount' => $e->getMessage(),
            ]);
        }

        return to_route('checks.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Check')])
        );

    }

    /**
     * Display the specified resource.
     */
    public function show(Check $check): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Check $check): Response
    {
        $walletOptions = SelectOption::create(ChurchWallet::all());
        $memberOptions = SelectOption::create(Member::all(), labels: ['name', 'last_name']);
        $checkTypesOptions = CheckType::options();
        $expenseTypesOptions = SelectOption::create(ExpenseType::all());

        return Inertia::render('checks/edit', [
            'walletOptions' => $walletOptions,
            'memberOptions' => $memberOptions,
            'checkTypesOptions' => $checkTypesOptions,
            'expenseTypesOptions' => $expenseTypesOptions,
            'check' => new CheckResource($check),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCheckRequest $request, Check $check, UpdateCheckAction $action): RedirectResponse
    {
        /**
         * @var array{amount:string,member_id:string,date:string,type:string,wallet_id:string,note:string|null,expense_type_id:string} $validated
         */
        $validated = $request->validated();
        try {

            $action->handle($check, $validated);

        } catch (WalletException $e) {
            return back()->with(
                FlashMessageKey::ERROR->value,
                $e->getMessage()
            )->withErrors([
                'amount' => $e->getMessage(),
            ]);
        }

        return to_route('checks.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Check')])
        );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Check $check, DeleteCheckAction $action): RedirectResponse
    {
        try {

            $action->handle($check);

        } catch (WalletException $e) {
            return back()->with(
                FlashMessageKey::ERROR->value,
                $e->getMessage()
            )->withErrors([
                'amount' => $e->getMessage(),
            ]);
        }

        return to_route('checks.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Check')])
        );
    }
}
