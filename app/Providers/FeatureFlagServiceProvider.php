<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\ChurchFeature;
use App\Models\Church;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;

final class FeatureFlagServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    #[\Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Feature::useMorphMap();
        Feature::resolveScopeUsing(fn (): ?Church => Church::current());

        $churchFeatures = ChurchFeature::values();

        foreach ($churchFeatures as $featureName) {
            Feature::define($featureName, false);
        }
    }
}
