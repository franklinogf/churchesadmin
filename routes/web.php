<?php

declare(strict_types=1);

use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

foreach (config('tenancy.identification.central_domains') as $domain) {
    Route::domain($domain)->group(function (): void {
        Route::get('/locale/{locale}', function (string $locale) {
            session(['locale' => $locale]);

            return redirect()->back();
        })->name('root.locale');
        Route::get('/', fn () => to_route('root.home', app()->getLocale()))->name('root.index');
        Route::middleware(SetLocale::class)
            ->name('root.')
            ->group(function (): void {
                Route::get('/', fn () => Inertia::render('welcome'))->name('home');

            });
    });
}
