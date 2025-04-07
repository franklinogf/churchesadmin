<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\LanguageCode;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $locale = $request->user()?->language->value
            ?? $request->cookie('locale')
            ?? session('locale')
            ?? config('app.locale');

        if (! in_array($locale, LanguageCode::values(), true)) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);

        session(['locale' => $locale]);

        cookie()->queue(cookie('locale', $locale, 60 * 24 * 30)); // 30 days

        return $next($request);
    }
}
