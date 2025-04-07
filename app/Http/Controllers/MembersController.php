<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\CivilStatus;
use App\Enums\Gender;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $members = Member::all();

        return Inertia::render('members/index', ['members' => $members]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('members/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        /**
         * @var array<string,mixed> $validated
         */
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'last_name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'unique:members,email'],
            'phone' => ['required', 'phone'],
            'gender' => ['required', 'string', Rule::enum(Gender::class)],
            'dob' => ['required', 'date'],
            'civil_status' => ['required', 'string', Rule::enum(CivilStatus::class)],
        ]);

        Member::create($validated);

        return redirect()->route('members.index')->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void
    {
        //
    }
}
