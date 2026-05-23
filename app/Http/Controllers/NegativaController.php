<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Negativa\CreateNegativaAction;
use App\Actions\Negativa\DeleteNegativaAction;
use App\Actions\Negativa\UpdateNegativaAction;
use App\Enums\FlashMessageKey;
use App\Http\Requests\Negativa\StoreNegativaRequest;
use App\Http\Requests\Negativa\UpdateNegativaRequest;
use App\Http\Resources\Negativa\NegativaResource;
use App\Models\Negativa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class NegativaController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('viewAny', Negativa::class);

        $negativas = Negativa::query()
            ->latest()
            ->get();

        return Inertia::render('main/books/negativa/index', [
            'negativas' => NegativaResource::collection($negativas),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Negativa::class);

        return Inertia::render('main/books/negativa/create');
    }

    public function edit(Negativa $negativa): Response
    {
        Gate::authorize('update', $negativa);

        return Inertia::render('main/books/negativa/edit', [
            'negativa' => new NegativaResource($negativa),
        ]);
    }

    public function store(StoreNegativaRequest $request, CreateNegativaAction $action): RedirectResponse
    {
        Gate::authorize('create', Negativa::class);

        $action->handle($request->validated());

        return to_route('books.negativa.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Negativa')])
        );
    }

    public function update(
        UpdateNegativaRequest $request,
        Negativa $negativa,
        UpdateNegativaAction $action,
    ): RedirectResponse {
        Gate::authorize('update', $negativa);

        $action->handle($negativa, $request->validated());

        return to_route('books.negativa.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Negativa')])
        );
    }

    public function destroy(Negativa $negativa, DeleteNegativaAction $action): RedirectResponse
    {
        Gate::authorize('delete', $negativa);

        $action->handle($negativa);

        return to_route('books.negativa.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Negativa')])
        );
    }
}
