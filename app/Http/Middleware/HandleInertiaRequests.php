<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\FlashMessageKey;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

final class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        /**
         * @var array<string, mixed> $parentShare
         */
        $parentShare = parent::share($request);

        return [
            ...$parentShare,
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn (): array => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sidebarOpen' => $request->cookie('sidebar_state') === 'true',
            'flash' => $this->getFlashMessages($request),
        ];
    }

    /**
     * Get flash messages from the session.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    protected function getFlashMessages(Request $request): array
    {
        return [
            FlashMessageKey::SUCCESS->value => $request->session()->get(FlashMessageKey::SUCCESS->value),
            FlashMessageKey::ERROR->value => $request->session()->get(FlashMessageKey::ERROR->value),
        ];
    }
}
