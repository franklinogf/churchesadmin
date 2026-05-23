<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\MarriageCertificate\CreateMarriageCertificateAction;
use App\Actions\MarriageCertificate\DeleteMarriageCertificateAction;
use App\Actions\MarriageCertificate\UpdateMarriageCertificateAction;
use App\Enums\FlashMessageKey;
use App\Http\Requests\MarriageCertificate\StoreMarriageCertificateRequest;
use App\Http\Requests\MarriageCertificate\UpdateMarriageCertificateRequest;
use App\Http\Resources\MarriageCertificate\MarriageCertificateResource;
use App\Models\MarriageCertificate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class MarriageCertificateController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('viewAny', MarriageCertificate::class);

        $marriageCertificates = MarriageCertificate::query()
            ->latest()
            ->get();

        return Inertia::render('main/books/marriage/index', [
            'marriageCertificates' => MarriageCertificateResource::collection($marriageCertificates),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', MarriageCertificate::class);

        return Inertia::render('main/books/marriage/create');
    }

    public function edit(MarriageCertificate $marriageCertificate): Response
    {
        Gate::authorize('update', $marriageCertificate);

        return Inertia::render('main/books/marriage/edit', [
            'marriageCertificate' => new MarriageCertificateResource($marriageCertificate),
        ]);
    }

    public function store(StoreMarriageCertificateRequest $request, CreateMarriageCertificateAction $action): RedirectResponse
    {
        Gate::authorize('create', MarriageCertificate::class);

        $action->handle($request->validated());

        return to_route('books.marriage.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Marriage Certificate')])
        );
    }

    public function update(
        UpdateMarriageCertificateRequest $request,
        MarriageCertificate $marriageCertificate,
        UpdateMarriageCertificateAction $action,
    ): RedirectResponse {
        Gate::authorize('update', $marriageCertificate);

        $action->handle($marriageCertificate, $request->validated());

        return to_route('books.marriage.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Marriage Certificate')])
        );
    }

    public function destroy(MarriageCertificate $marriageCertificate, DeleteMarriageCertificateAction $action): RedirectResponse
    {
        Gate::authorize('delete', $marriageCertificate);

        $action->handle($marriageCertificate);

        return to_route('books.marriage.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Marriage Certificate')])
        );
    }
}
