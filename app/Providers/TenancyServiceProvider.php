<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\ServiceProvider;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Controllers\TenantAssetController;
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Jobs;
use Stancl\Tenancy\Listeners;
use Stancl\Tenancy\Middleware;
use Stancl\Tenancy\ResourceSyncing;

final class TenancyServiceProvider extends ServiceProvider
{
    // By default, no namespace is used to support the callable array syntax.
    public static string $controllerNamespace = '';

    public function events(): array
    {
        return [
            // Tenant events
            Events\CreatingTenant::class => [],
            Events\TenantCreated::class => [
                JobPipeline::make([
                    Jobs\CreateDatabase::class,
                    Jobs\MigrateDatabase::class,
                    Jobs\SeedDatabase::class,
                    Jobs\CreateStorageSymlinks::class,

                    // Your own jobs to prepare the tenant.
                    // Provision API keys, create S3 buckets, anything you want!
                ])->send(fn (Events\TenantCreated $event): \Stancl\Tenancy\Contracts\Tenant => $event->tenant)->shouldBeQueued(false), // `false` by default, but you likely want to make this `true` in production.

                Listeners\CreateTenantStorage::class,
            ],
            Events\SavingTenant::class => [],
            Events\TenantSaved::class => [],
            Events\UpdatingTenant::class => [],
            Events\TenantUpdated::class => [],
            Events\DeletingTenant::class => [
                JobPipeline::make([
                    Jobs\DeleteDomains::class,
                    Jobs\RemoveStorageSymlinks::class,
                ])->send(fn (Events\DeletingTenant $event): \Stancl\Tenancy\Contracts\Tenant => $event->tenant)->shouldBeQueued(false),

                Listeners\DeleteTenantStorage::class,
            ],
            Events\TenantDeleted::class => [
                JobPipeline::make([
                    Jobs\DeleteDatabase::class,
                ])->send(fn (Events\TenantDeleted $event): \Stancl\Tenancy\Contracts\Tenant => $event->tenant)->shouldBeQueued(false), // `false` by default, but you probably want to make this `true` for production.
            ],

            Events\TenantMaintenanceModeEnabled::class => [],
            Events\TenantMaintenanceModeDisabled::class => [],

            // Pending tenant events
            Events\CreatingPendingTenant::class => [],
            Events\PendingTenantCreated::class => [],
            Events\PullingPendingTenant::class => [],
            Events\PendingTenantPulled::class => [],

            // Domain events
            Events\CreatingDomain::class => [],
            Events\DomainCreated::class => [],
            Events\SavingDomain::class => [],
            Events\DomainSaved::class => [],
            Events\UpdatingDomain::class => [],
            Events\DomainUpdated::class => [],
            Events\DeletingDomain::class => [],
            Events\DomainDeleted::class => [],

            // Database events
            Events\DatabaseCreated::class => [],
            Events\DatabaseMigrated::class => [],
            Events\DatabaseSeeded::class => [],
            Events\DatabaseRolledBack::class => [],
            Events\DatabaseDeleted::class => [],

            // Tenancy events
            Events\InitializingTenancy::class => [],
            Events\TenancyInitialized::class => [
                Listeners\BootstrapTenancy::class,
            ],

            Events\EndingTenancy::class => [],
            Events\TenancyEnded::class => [
                function (Events\TenancyEnded $event): void {
                    $permissionRegistrar = app(\Spatie\Permission\PermissionRegistrar::class);
                    $permissionRegistrar->cacheKey = 'spatie.permission.cache';
                },
                Listeners\RevertToCentralContext::class,
            ],

            Events\BootstrappingTenancy::class => [],
            Events\TenancyBootstrapped::class => [
                function (Events\TenancyBootstrapped $event): void {
                    $permissionRegistrar = app(\Spatie\Permission\PermissionRegistrar::class);
                    $permissionRegistrar->cacheKey = 'spatie.permission.cache.tenant.'.$event->tenancy->tenant->getTenantKey();
                },
            ],
            Events\RevertingToCentralContext::class => [],
            Events\RevertedToCentralContext::class => [],

            // Resource syncing
            ResourceSyncing\Events\SyncedResourceSaved::class => [
                ResourceSyncing\Listeners\UpdateOrCreateSyncedResource::class,
            ],
            ResourceSyncing\Events\SyncMasterDeleted::class => [
                ResourceSyncing\Listeners\DeleteResourcesInTenants::class,
            ],
            ResourceSyncing\Events\SyncMasterRestored::class => [
                ResourceSyncing\Listeners\RestoreResourcesInTenants::class,
            ],
            ResourceSyncing\Events\CentralResourceAttachedToTenant::class => [
                ResourceSyncing\Listeners\CreateTenantResource::class,
            ],
            ResourceSyncing\Events\CentralResourceDetachedFromTenant::class => [
                ResourceSyncing\Listeners\DeleteResourceInTenant::class,
            ],
            // Fired only when a synced resource is changed in a different DB than the origin DB (to avoid infinite loops)
            ResourceSyncing\Events\SyncedResourceSavedInForeignDatabase::class => [],

            // Storage symlinks
            Events\CreatingStorageSymlink::class => [],
            Events\StorageSymlinkCreated::class => [],
            Events\RemovingStorageSymlink::class => [],
            Events\StorageSymlinkRemoved::class => [],
        ];
    }

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->bootEvents();
        $this->mapRoutes();

        $this->makeTenancyMiddlewareHighestPriority();
        $this->overrideUrlInTenantContext();

        TenantAssetController::$headers = ['cache-control' => 'public, max-age=3600'];

        // // Include soft deleted resources in synced resource queries.
        // ResourceSyncing\Listeners\UpdateOrCreateSyncedResource::$scopeGetModelQuery = function (Builder $query) {
        //     if ($query->hasMacro('withTrashed')) {
        //         $query->withTrashed();
        //     }
        // };

        // // To make Livewire v3 work with Tenancy, make the update route universal.
        // Livewire::setUpdateRoute(function ($handle) {
        //     return RouteFacade::post('/livewire/update', $handle)->middleware(['web', 'universal', \Stancl\Tenancy\Tenancy::defaultMiddleware()]);
        // });
    }

    /**
     * Set \Stancl\Tenancy\Bootstrappers\RootUrlBootstrapper::$rootUrlOverride here
     * to override the root URL used in CLI while in tenant context.
     *
     * @see \Stancl\Tenancy\Bootstrappers\RootUrlBootstrapper
     */
    private function overrideUrlInTenantContext(): void
    {
        // \Stancl\Tenancy\Bootstrappers\RootUrlBootstrapper::$rootUrlOverride = function (Tenant $tenant, string $originalRootUrl) {
        //     $tenantDomain = $tenant instanceof \Stancl\Tenancy\Contracts\SingleDomainTenant
        //     ? $tenant->domain
        //     : $tenant->domains->first()->domain;
        //     $scheme = str($originalRootUrl)->before('://');
        //
        //     // If you're using domain identification:
        //     return $scheme . '://' . $tenantDomain . '/';
        //
        //     // If you're using subdomain identification:
        //     $originalDomain = str($originalRootUrl)->after($scheme . '://');
        //     return $scheme . '://' . $tenantDomain . '.' . $originalDomain . '/';
        // };
    }

    private function bootEvents(): void
    {
        foreach ($this->events() as $event => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof JobPipeline) {
                    $listener = $listener->toListener();
                }

                Event::listen($event, $listener);
            }
        }
    }

    private function mapRoutes(): void
    {
        $this->app->booted(function (): void {
            if (file_exists(base_path('routes/tenant.php'))) {
                RouteFacade::namespace(static::$controllerNamespace)
                    ->middleware('tenant')
                    ->group(base_path('routes/tenant.php'));
            }

            // $this->cloneRoutes();
        });
    }

    private function makeTenancyMiddlewareHighestPriority(): void
    {
        // PreventAccessFromUnwantedDomains has even higher priority than the identification middleware
        $tenancyMiddleware = array_merge([Middleware\PreventAccessFromUnwantedDomains::class], config('tenancy.identification.middleware'));

        foreach (array_reverse($tenancyMiddleware) as $middleware) {
            $this->app[\Illuminate\Contracts\Http\Kernel::class]->prependToMiddlewarePriority($middleware);
        }
    }
}
