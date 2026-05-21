<?php

declare(strict_types=1);

namespace App\Providers;

use BackedEnum;
use Carbon\Carbon;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Bootstrappers\BroadcastChannelPrefixBootstrapper;
use Stancl\Tenancy\Contracts\Tenant;
use Stancl\Tenancy\Controllers\TenantAssetController;
use Stancl\Tenancy\Events\BootstrappingTenancy;
use Stancl\Tenancy\Events\CreatingDomain;
use Stancl\Tenancy\Events\CreatingPendingTenant;
use Stancl\Tenancy\Events\CreatingStorageSymlink;
use Stancl\Tenancy\Events\CreatingTenant;
use Stancl\Tenancy\Events\DatabaseCreated;
use Stancl\Tenancy\Events\DatabaseDeleted;
use Stancl\Tenancy\Events\DatabaseMigrated;
use Stancl\Tenancy\Events\DatabaseRolledBack;
use Stancl\Tenancy\Events\DatabaseSeeded;
use Stancl\Tenancy\Events\DeletingDomain;
use Stancl\Tenancy\Events\DeletingTenant;
use Stancl\Tenancy\Events\DomainCreated;
use Stancl\Tenancy\Events\DomainDeleted;
use Stancl\Tenancy\Events\DomainSaved;
use Stancl\Tenancy\Events\DomainUpdated;
use Stancl\Tenancy\Events\EndingTenancy;
use Stancl\Tenancy\Events\InitializingTenancy;
use Stancl\Tenancy\Events\PendingTenantCreated;
use Stancl\Tenancy\Events\PendingTenantPulled;
use Stancl\Tenancy\Events\PullingPendingTenant;
use Stancl\Tenancy\Events\RemovingStorageSymlink;
use Stancl\Tenancy\Events\RevertedToCentralContext;
use Stancl\Tenancy\Events\RevertingToCentralContext;
use Stancl\Tenancy\Events\SavingDomain;
use Stancl\Tenancy\Events\SavingTenant;
use Stancl\Tenancy\Events\StorageSymlinkCreated;
use Stancl\Tenancy\Events\StorageSymlinkRemoved;
use Stancl\Tenancy\Events\TenancyBootstrapped;
use Stancl\Tenancy\Events\TenancyEnded;
use Stancl\Tenancy\Events\TenancyInitialized;
use Stancl\Tenancy\Events\TenantCreated;
use Stancl\Tenancy\Events\TenantDeleted;
use Stancl\Tenancy\Events\TenantMaintenanceModeDisabled;
use Stancl\Tenancy\Events\TenantMaintenanceModeEnabled;
use Stancl\Tenancy\Events\TenantSaved;
use Stancl\Tenancy\Events\TenantUpdated;
use Stancl\Tenancy\Events\UpdatingDomain;
use Stancl\Tenancy\Events\UpdatingTenant;
use Stancl\Tenancy\Jobs\CreateDatabase;
use Stancl\Tenancy\Jobs\CreateStorageSymlinks;
use Stancl\Tenancy\Jobs\DeleteDatabase;
use Stancl\Tenancy\Jobs\DeleteDomains;
use Stancl\Tenancy\Jobs\MigrateDatabase;
use Stancl\Tenancy\Jobs\RemoveStorageSymlinks;
use Stancl\Tenancy\Listeners\BootstrapTenancy;
use Stancl\Tenancy\Listeners\CreateTenantStorage;
use Stancl\Tenancy\Listeners\DeleteTenantStorage;
use Stancl\Tenancy\Listeners\RevertToCentralContext;
use Stancl\Tenancy\Middleware\PreventAccessFromUnwantedDomains;
use Stancl\Tenancy\ResourceSyncing\Events\CentralResourceAttachedToTenant;
use Stancl\Tenancy\ResourceSyncing\Events\CentralResourceDetachedFromTenant;
use Stancl\Tenancy\ResourceSyncing\Events\SyncedResourceSaved;
use Stancl\Tenancy\ResourceSyncing\Events\SyncedResourceSavedInForeignDatabase;
use Stancl\Tenancy\ResourceSyncing\Events\SyncMasterDeleted;
use Stancl\Tenancy\ResourceSyncing\Events\SyncMasterRestored;
use Stancl\Tenancy\ResourceSyncing\Listeners\CreateTenantResource;
use Stancl\Tenancy\ResourceSyncing\Listeners\DeleteResourceInTenant;
use Stancl\Tenancy\ResourceSyncing\Listeners\DeleteResourcesInTenants;
use Stancl\Tenancy\ResourceSyncing\Listeners\RestoreResourcesInTenants;
use Stancl\Tenancy\ResourceSyncing\Listeners\UpdateOrCreateSyncedResource;

final class TenancyServiceProvider extends ServiceProvider
{
    // By default, no namespace is used to support the callable array syntax.
    public static string $controllerNamespace = '';

    public function events(): array
    {
        return [
            // Tenant events
            CreatingTenant::class => [],
            TenantCreated::class => [
                JobPipeline::make([
                    CreateDatabase::class,
                    MigrateDatabase::class,
                    // Jobs\SeedDatabase::class,
                    CreateStorageSymlinks::class,

                    // Your own jobs to prepare the tenant.
                    // Provision API keys, create S3 buckets, anything you want!
                ])->send(fn (TenantCreated $event): Tenant => $event->tenant)->shouldBeQueued(false), // `false` by default, but you likely want to make this `true` in production.

                CreateTenantStorage::class,
            ],
            SavingTenant::class => [],
            TenantSaved::class => [],
            UpdatingTenant::class => [],
            TenantUpdated::class => [],
            DeletingTenant::class => [
                JobPipeline::make([
                    DeleteDomains::class,
                    RemoveStorageSymlinks::class,
                ])->send(fn (DeletingTenant $event): Tenant => $event->tenant)->shouldBeQueued(false),

                DeleteTenantStorage::class,
            ],
            TenantDeleted::class => [
                JobPipeline::make([
                    DeleteDatabase::class,
                ])->send(fn (TenantDeleted $event): Tenant => $event->tenant)->shouldBeQueued(false), // `false` by default, but you probably want to make this `true` for production.
            ],

            TenantMaintenanceModeEnabled::class => [],
            TenantMaintenanceModeDisabled::class => [],

            // Pending tenant events
            CreatingPendingTenant::class => [],
            PendingTenantCreated::class => [],
            PullingPendingTenant::class => [],
            PendingTenantPulled::class => [],

            // Domain events
            CreatingDomain::class => [],
            DomainCreated::class => [],
            SavingDomain::class => [],
            DomainSaved::class => [],
            UpdatingDomain::class => [],
            DomainUpdated::class => [],
            DeletingDomain::class => [],
            DomainDeleted::class => [],

            // Database events
            DatabaseCreated::class => [],
            DatabaseMigrated::class => [],
            DatabaseSeeded::class => [],
            DatabaseRolledBack::class => [],
            DatabaseDeleted::class => [],

            // Tenancy events
            InitializingTenancy::class => [],
            TenancyInitialized::class => [
                BootstrapTenancy::class,
                function (TenancyInitialized $event): void {
                    // Ensure locale is converted to a string if it's an enum
                    $locale = $event->tenancy->tenant->locale;
                    if ($locale instanceof BackedEnum) {
                        $locale = $locale->value;
                    }

                    Carbon::setLocale($locale ?? config('app.locale'));
                },
            ],

            EndingTenancy::class => [],
            TenancyEnded::class => [
                function (TenancyEnded $event): void {
                    $permissionRegistrar = resolve(PermissionRegistrar::class);
                    $permissionRegistrar->cacheKey = 'spatie.permission.cache';
                },
                RevertToCentralContext::class,
            ],

            BootstrappingTenancy::class => [],
            TenancyBootstrapped::class => [
                function (TenancyBootstrapped $event): void {
                    $permissionRegistrar = resolve(PermissionRegistrar::class);
                    $permissionRegistrar->cacheKey = 'spatie.permission.cache.tenant.'.$event->tenancy->tenant->getTenantKey();
                },
            ],
            RevertingToCentralContext::class => [],
            RevertedToCentralContext::class => [],

            // Resource syncing
            SyncedResourceSaved::class => [
                UpdateOrCreateSyncedResource::class,
            ],
            SyncMasterDeleted::class => [
                DeleteResourcesInTenants::class,
            ],
            SyncMasterRestored::class => [
                RestoreResourcesInTenants::class,
            ],
            CentralResourceAttachedToTenant::class => [
                CreateTenantResource::class,
            ],
            CentralResourceDetachedFromTenant::class => [
                DeleteResourceInTenant::class,
            ],
            // Fired only when a synced resource is changed in a different DB than the origin DB (to avoid infinite loops)
            SyncedResourceSavedInForeignDatabase::class => [],

            // Storage symlinks
            CreatingStorageSymlink::class => [],
            StorageSymlinkCreated::class => [],
            RemovingStorageSymlink::class => [],
            StorageSymlinkRemoved::class => [],
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
        BroadcastChannelPrefixBootstrapper::reverb();

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
                RouteFacade::namespace(self::$controllerNamespace)
                    ->middleware('tenant')
                    ->group(base_path('routes/tenant.php'));
            }

            // $this->cloneRoutes();
        });
    }

    private function makeTenancyMiddlewareHighestPriority(): void
    {
        // PreventAccessFromUnwantedDomains has even higher priority than the identification middleware
        $tenancyMiddleware = array_merge([PreventAccessFromUnwantedDomains::class], config('tenancy.identification.middleware'));

        foreach (array_reverse($tenancyMiddleware) as $middleware) {
            $this->app->make(Kernel::class)->prependToMiddlewarePriority($middleware);
        }
    }
}
