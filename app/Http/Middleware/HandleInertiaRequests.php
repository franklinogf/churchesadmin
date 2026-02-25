<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\ChurchFeature;
use App\Enums\FlashMessageKey;
use App\Enums\LanguageCode;
use App\Http\Resources\ChurchResource;
use App\Http\Resources\User\AuthUserResource;
use App\Models\Church;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Override;

final class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    #[Override]
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    #[Override]
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
    #[Override]
    public function share(Request $request): array
    {
        /**
         * @var array<string, mixed> $parentShare
         */
        $parentShare = parent::share($request);

        return [
            ...$parentShare,
            'auth' => [
                'user' => $request->user()
                    ? new AuthUserResource($request->user())
                    : null,
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'flash' => $this->getFlashMessages($request),
            'availableLocales' => LanguageCode::options(),
            'appName' => config('app.name'),
            'environment' => app()->environment(),
            'church' => ($church = Church::current()) instanceof Church ? new ChurchResource($church) : null,
            'features' => $this->getChurchFeatures(),
        ];
    }

    /**
     * Get flash messages from the session.
     *
     * @return array<string, mixed>
     */
    private function getFlashMessages(Request $request): array
    {
        return [
            FlashMessageKey::SUCCESS->value => $request->session()->get(FlashMessageKey::SUCCESS->value),
            FlashMessageKey::ERROR->value => $request->session()->get(FlashMessageKey::ERROR->value),
            FlashMessageKey::MESSAGE->value => $request->session()->get(FlashMessageKey::MESSAGE->value),
        ];
    }

    /**
     * Get the features enabled for the current church.
     *
     * @return array<string, bool>
     */
    private function getChurchFeatures(): array
    {
        $church = Church::current();
        if (! $church instanceof Church) {
            return [];
        }
        /**
         * @var array<string,bool>
         */
        $features = collect(ChurchFeature::values())
            ->mapWithKeys(fn (string $feature): array => [
                $feature => $church->features()->active($feature),
            ])
            ->toArray();

        return $features;
    }
}
