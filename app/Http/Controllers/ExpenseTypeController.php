<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FlashMessageKey;
use App\Http\Requests\Code\StoreExpenseTypeRequest;
use App\Http\Requests\Code\UpdateExpenseTypeRequest;
use App\Http\Resources\Codes\ExpenseTypeResource;
use App\Models\ExpenseType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class ExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', ExpenseType::class);

        $expenseTypes = ExpenseType::latest()->get();

        return Inertia::render('codes/expenseTypes/index', [
            'expenseTypes' => ExpenseTypeResource::collection($expenseTypes),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseTypeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        ExpenseType::create($validated);

        return to_route('codes.expenseTypes.index')->with(FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => 'Expense type']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseTypeRequest $request, ExpenseType $expenseType): RedirectResponse
    {
        Gate::authorize('update', $expenseType);

        $expenseType->update($request->validated());

        return to_route('codes.expenseTypes.index')->with(FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => 'Expense type']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseType $expenseType): RedirectResponse
    {
        Gate::authorize('delete', $expenseType);

        $expenseType->delete();

        return to_route('codes.expenseTypes.index')->with(FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => 'Expense type']));
    }
}
