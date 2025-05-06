<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Features\TenantConfig;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;

final class ChurchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        TenantConfig::$storageToConfigMap = [
            'locale' => 'app.locale',
        ];
        InitializeTenancyBySubdomain::$onFail = fn () => abort(404);

    }
}
