<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\Visit\VisitResource;
use App\Models\Visit;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class VisitFollowUpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Visit $visit): Response
    {
        return Inertia::render('main/visits/follow-ups/index', [
            'visit' => new VisitResource($visit),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
