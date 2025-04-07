<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\CivilStatus;
use App\Enums\FlashMessageKey;
use App\Enums\Gender;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class MemberController extends Controller
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
        $genders = Gender::options();
        $civilStatuses = CivilStatus::options();

        return Inertia::render('members/create', [
            'genders' => $genders,
            'civilStatuses' => $civilStatuses,
        ]);
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
            'dob' => ['required', 'date:Y-m-d'],
            'civil_status' => ['required', 'string', Rule::enum(CivilStatus::class)],
        ]);

        Member::create($validated);

        return redirect()->route('members.index')->with(FlashMessageKey::SUCCESS->value, 'Member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member): Response
    {
        return Inertia::render('members/edit', [
            'member' => $member,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member): RedirectResponse
    {

        /**
         * @var array<string,mixed> $validated
         */
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'last_name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', Rule::unique('members')->ignore($member->id)],
            'phone' => ['required', 'phone'],
            'gender' => ['required', 'string', Rule::enum(Gender::class)],
            'dob' => ['required', 'date'],
            'civil_status' => ['required', 'string', Rule::enum(CivilStatus::class)],
        ]);

        $member->update($validated);

        return redirect()->route('members.index')->with(FlashMessageKey::SUCCESS->value, 'Member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();

        return redirect()->route('members.index')->with(FlashMessageKey::SUCCESS->value, 'Member deleted successfully.');
    }
}
