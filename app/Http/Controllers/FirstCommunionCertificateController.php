<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\FirstCommunionCertificate\CreateFirstCommunionCertificateAction;
use App\Actions\FirstCommunionCertificate\DeleteFirstCommunionCertificateAction;
use App\Actions\FirstCommunionCertificate\UpdateFirstCommunionCertificateAction;
use App\Enums\FlashMessageKey;
use App\Http\Requests\FirstCommunionCertificate\StoreFirstCommunionCertificateRequest;
use App\Http\Requests\FirstCommunionCertificate\UpdateFirstCommunionCertificateRequest;
use App\Http\Resources\FirstCommunionCertificate\FirstCommunionCertificateResource;
use App\Models\FirstCommunionCertificate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class FirstCommunionCertificateController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('viewAny', FirstCommunionCertificate::class);

        $firstCommunionCertificates = FirstCommunionCertificate::query()
            ->latest()
            ->get();

        return Inertia::render('main/books/communion/index', [
            'firstCommunionCertificates' => FirstCommunionCertificateResource::collection($firstCommunionCertificates),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', FirstCommunionCertificate::class);

        return Inertia::render('main/books/communion/create');
    }

    public function edit(FirstCommunionCertificate $firstCommunionCertificate): Response
    {
        Gate::authorize('update', $firstCommunionCertificate);

        return Inertia::render('main/books/communion/edit', [
            'firstCommunionCertificate' => new FirstCommunionCertificateResource($firstCommunionCertificate),
        ]);
    }

    public function store(StoreFirstCommunionCertificateRequest $request, CreateFirstCommunionCertificateAction $action): RedirectResponse
    {
        Gate::authorize('create', FirstCommunionCertificate::class);

        $action->handle($request->validated());

        return to_route('books.communion.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('First Communion Certificate')])
        );
    }

    public function update(
        UpdateFirstCommunionCertificateRequest $request,
        FirstCommunionCertificate $firstCommunionCertificate,
        UpdateFirstCommunionCertificateAction $action,
    ): RedirectResponse {
        Gate::authorize('update', $firstCommunionCertificate);

        $action->handle($firstCommunionCertificate, $request->validated());

        return to_route('books.communion.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('First Communion Certificate')])
        );
    }

    public function destroy(FirstCommunionCertificate $firstCommunionCertificate, DeleteFirstCommunionCertificateAction $action): RedirectResponse
    {
        Gate::authorize('delete', $firstCommunionCertificate);

        $action->handle($firstCommunionCertificate);

        return to_route('books.communion.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('First Communion Certificate')])
        );
    }
}
