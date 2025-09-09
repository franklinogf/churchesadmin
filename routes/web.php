<?php

declare(strict_types=1);

use App\Models\Church;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

foreach (config('tenancy.identification.central_domains') as $domain) {
    Route::domain($domain)->group(function (): void {
        Route::get('/locale/{locale}', function (string $locale): RedirectResponse {
            session(['locale' => $locale]);

            return redirect()->back();
        })->name('root.locale');
        Route::get('/', fn (): RedirectResponse => to_route('root.home', app()->getLocale()))->name('root.index');
        Route::name('root.')
            ->group(function (): void {
                Route::get('/', function (): Response {
                    $church = Church::where('name', 'Demo Church')->first();
                    $url = create_tenant_url($church, 'login');

                    return Inertia::render('welcome', ['demoLink' => $url]);
                })->name('home');

            });
    });
}
