<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SessionName;
use App\Services\Session\SessionService;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

final class SessionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, SessionService $sessionService): RedirectResponse
    {
        $request->validateWithBag('session', [
            'name' => ['required', 'string', 'min:1', Rule::enum(SessionName::class)],
            'value' => ['required'],
            'redirect_to' => ['required', 'string', 'min:1', function (string $attribute, string $value, Closure $fail): void {
                if (! Route::has($value)) {
                    $fail('The specified route does not exist.');
                }
            }],
        ]);

        $sessionService->create(
            SessionName::from($request->string('name')->value()),
            $request->input('value')
        );

        return to_route($request->string('redirect_to')->value());
    }
}
