<?php

declare(strict_types=1);

use App\Http\Controllers\Root\HomeController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.identification.central_domains') as $domain) {
    Route::domain($domain)->group(function (): void {
        Route::get('/locale/{locale}', function (string $locale): RedirectResponse {
            session(['locale' => $locale]);

            return redirect()->back();
        })->name('root.locale');
        Route::get('/', fn (): RedirectResponse => to_route('root.home', app()->getLocale()))->name('root.index');
        Route::name('root.')
            ->group(function (): void {
                Route::get('/', HomeController::class)->name('home');

            });
    });
}
