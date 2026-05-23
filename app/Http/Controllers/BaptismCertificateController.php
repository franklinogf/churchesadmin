<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\BaptismCertificate\CreateBaptismCertificateAction;
use App\Actions\BaptismCertificate\DeleteBaptismCertificateAction;
use App\Actions\BaptismCertificate\UpdateBaptismCertificateAction;
use App\Enums\FlashMessageKey;
use App\Http\Requests\BaptismCertificate\StoreBaptismCertificateRequest;
use App\Http\Requests\BaptismCertificate\UpdateBaptismCertificateRequest;
use App\Http\Resources\BaptismCertificate\BaptismCertificateResource;
use App\Models\BaptismCertificate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class BaptismCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', BaptismCertificate::class);

        $baptismCertificates = BaptismCertificate::query()
            ->latest()
            ->get();

        return Inertia::render('main/books/baptism/index', [
            'baptismCertificates' => BaptismCertificateResource::collection($baptismCertificates),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', BaptismCertificate::class);

        return Inertia::render('main/books/baptism/create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BaptismCertificate $baptismCertificate): Response
    {
        Gate::authorize('update', $baptismCertificate);

        return Inertia::render('main/books/baptism/edit', [
            'baptismCertificate' => new BaptismCertificateResource($baptismCertificate),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBaptismCertificateRequest $request, CreateBaptismCertificateAction $action): RedirectResponse
    {
        Gate::authorize('create', BaptismCertificate::class);

        $action->handle($request->validated());

        return to_route('books.baptism.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Baptism Certificate')])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateBaptismCertificateRequest $request,
        BaptismCertificate $baptismCertificate,
        UpdateBaptismCertificateAction $action,
    ): RedirectResponse {
        Gate::authorize('update', $baptismCertificate);

        $action->handle($baptismCertificate, $request->validated());

        return to_route('books.baptism.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Baptism Certificate')])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BaptismCertificate $baptismCertificate, DeleteBaptismCertificateAction $action): RedirectResponse
    {
        Gate::authorize('delete', $baptismCertificate);

        $action->handle($baptismCertificate);

        return to_route('books.baptism.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Baptism Certificate')])
        );
    }
}
