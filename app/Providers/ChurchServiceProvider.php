<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Church;
use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Bootstrappers\RootUrlBootstrapper;
use Stancl\Tenancy\Bootstrappers\TenantConfigBootstrapper;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;

final class ChurchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        TenantConfigBootstrapper::$storageToConfigMap = [
            'locale' => 'app.locale',
            'name' => 'mail.from.name',
        ];
        InitializeTenancyBySubdomain::$onFail = fn () => abort(404);

        RootUrlBootstrapper::$rootUrlOverride = function (Church $tenant): string {
            return 'https://'.$tenant->domains->first()->domain.'/';
        };

    }
}
