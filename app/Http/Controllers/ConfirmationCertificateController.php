<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\ConfirmationCertificate\CreateConfirmationCertificateAction;
use App\Actions\ConfirmationCertificate\DeleteConfirmationCertificateAction;
use App\Actions\ConfirmationCertificate\UpdateConfirmationCertificateAction;
use App\Enums\FlashMessageKey;
use App\Http\Requests\ConfirmationCertificate\StoreConfirmationCertificateRequest;
use App\Http\Requests\ConfirmationCertificate\UpdateConfirmationCertificateRequest;
use App\Http\Resources\ConfirmationCertificate\ConfirmationCertificateResource;
use App\Models\ConfirmationCertificate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class ConfirmationCertificateController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('viewAny', ConfirmationCertificate::class);

        $confirmationCertificates = ConfirmationCertificate::query()
            ->latest()
            ->get();

        return Inertia::render('main/books/confirmation/index', [
            'confirmationCertificates' => ConfirmationCertificateResource::collection($confirmationCertificates),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', ConfirmationCertificate::class);

        return Inertia::render('main/books/confirmation/create');
    }

    public function edit(ConfirmationCertificate $confirmationCertificate): Response
    {
        Gate::authorize('update', $confirmationCertificate);

        return Inertia::render('main/books/confirmation/edit', [
            'confirmationCertificate' => new ConfirmationCertificateResource($confirmationCertificate),
        ]);
    }

    public function store(StoreConfirmationCertificateRequest $request, CreateConfirmationCertificateAction $action): RedirectResponse
    {
        Gate::authorize('create', ConfirmationCertificate::class);

        $action->handle($request->validated());

        return to_route('books.confirmation.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Confirmation Certificate')])
        );
    }

    public function update(
        UpdateConfirmationCertificateRequest $request,
        ConfirmationCertificate $confirmationCertificate,
        UpdateConfirmationCertificateAction $action,
    ): RedirectResponse {
        Gate::authorize('update', $confirmationCertificate);

        $action->handle($confirmationCertificate, $request->validated());

        return to_route('books.confirmation.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Confirmation Certificate')])
        );
    }

    public function destroy(ConfirmationCertificate $confirmationCertificate, DeleteConfirmationCertificateAction $action): RedirectResponse
    {
        Gate::authorize('delete', $confirmationCertificate);

        $action->handle($confirmationCertificate);

        return to_route('books.confirmation.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Confirmation Certificate')])
        );
    }
}
