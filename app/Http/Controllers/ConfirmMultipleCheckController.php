<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Check;
use Illuminate\Http\Request;

final class ConfirmMultipleCheckController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'checks' => ['required', 'array'],
            'checks.*.id' => ['required', 'exists:checks,id'],
            'initial_check_number' => ['required', 'numeric', 'min:1',
                function ($attribute, $value, $fail) {
                    if (Check::confirmed()->where('check_number', $value)->exists()) {
                        $fail('There is a check with this number.');
                    }
                },
            ],
            [
                'messages' => ['checks.required' => 'At least one check must be selected.'],
            ],
        ]);

        $checkNumber = $validated['initial_check_number'];

        Check::unconfirmed()->update(['check_number' => null]);

        foreach ($validated['checks'] as $checkData) {
            $check = Check::find($checkData['id']);
            $check->update(['check_number' => $checkNumber]);

            $check->transaction->wallet->confirm($check->transaction);
            $checkNumber++;
        }

        return back()->with('success', 'Checks confirmed successfully.');
    }
}
