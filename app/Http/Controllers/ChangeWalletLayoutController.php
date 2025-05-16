<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ChurchWallet;
use Illuminate\Http\Request;

final class ChangeWalletLayoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ChurchWallet $wallet)
    {
        $layout = $request->input('layout');

        if ($layout) {
            $wallet->checkLayout()->associate($layout);
        }

        $wallet->save();

        return back()->with('success', 'Check layout updated successfully.');
    }
}
