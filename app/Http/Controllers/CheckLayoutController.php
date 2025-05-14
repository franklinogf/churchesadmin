<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use NumberToWords\NumberToWords;

final class CheckLayoutController extends Controller
{
    public function edit()
    {
        $layout = [
            [
                'id' => 'payee',
                'label' => 'Franklin Omar Gonzalez Flores',
                'position' => [
                    'x' => 0,
                    'y' => 0,
                ],
            ],
            [
                'id' => 'amount',
                'label' => '1,000',
                'position' => [
                    'x' => 0,
                    'y' => 0,
                ],
            ],
            [
                'id' => 'amountWords',
                'withDollars' => false,
                'label' => NumberToWords::transformNumber(app()->getLocale(), 1000),
                'position' => [
                    'x' => 0,
                    'y' => 0,
                ],
            ],
            [
                'id' => 'date',
                'label' => '2023-10-01',
                'position' => [
                    'x' => 0,
                    'y' => 0,
                ],
            ],
            [
                'id' => 'memo',
                'label' => 'Memo',
                'position' => [
                    'x' => 0,
                    'y' => 0,
                ],
            ],
            [
                'id' => 'signature',
                'label' => 'Church Signature',
                'position' => [
                    'x' => 0,
                    'y' => 0,
                ],
            ],
        ];

        $layout = collect($layout)->map(function ($item, $index) {
            $isFirstTime = $item['position']['y'] === 0 && $item['position']['x'] === 0;
            $item['position'] = [
                'x' => $isFirstTime ? 0 : $item['position']['x'],
                'y' => $isFirstTime ? $index * 20 : $item['position']['y'],
            ];

            return $item;
        })->toArray();

        return Inertia::render('settings/church/check-layout', [
            'layout' => $layout,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'layout' => 'required|string',
        ]);

        // Save the layout to the database or perform any other necessary actions

        return back()->with('success', 'Check layout updated successfully.');
    }
}
