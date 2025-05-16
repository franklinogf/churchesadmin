<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\CheckLayoutField;
use App\Http\Resources\Wallet\CheckLayoutResource;
use App\Http\Resources\Wallet\ChurchWalletResource;
use App\Models\CheckLayout;
use App\Models\ChurchWallet;
use App\Support\SelectOption;
use Illuminate\Http\Request;
use Inertia\Inertia;

final class CheckLayoutController extends Controller
{
    public function store(Request $request, ChurchWallet $wallet)
    {

        $checkLayout = CheckLayout::create([
            'name' => $request->name,
            'width' => 455,
            'height' => 300,
            'fields' => CheckLayoutField::initialLayout(),
        ]);

        $checkLayout->addMediaFromRequest('image')->toMediaCollection();

        $wallet->checkLayout()->associate($checkLayout)->save();

        return to_route('wallets.check.edit', ['wallet' => $wallet, 'layout' => $checkLayout->id])
            ->with('success', 'Check layout created successfully.');
    }

    public function edit(Request $request, ChurchWallet $wallet)
    {
        $wallet->load('checkLayout');

        $checkLayoutId = $request->integer('layout', $wallet->checkLayout->id);

        $checkLayout = CheckLayout::find($checkLayoutId);

        $checkLayouts = SelectOption::create(CheckLayout::all());

        return Inertia::render('wallets/check-layout', [
            'wallet' => new ChurchWalletResource($wallet),
            'checkLayouts' => $checkLayouts,
            'checkLayout' => $checkLayout ? new CheckLayoutResource($checkLayout) : null,
        ]);
    }

    public function update(Request $request, ChurchWallet $wallet, CheckLayout $checkLayout)
    {

        $checkLayout->update(['fields' => $request->fields]);

        // $request->validate([
        //     'layout' => 'required|string',
        // ]);

        // $wallet->checkLayout->fields = json_decode($request->layout, true);
        // $wallet->checkLayout->save();

        return back()->with('success', 'Check layout updated successfully.');
    }
}
