<?php

declare(strict_types=1);

namespace Tests;

use App\Enums\LanguageCode;
use App\Models\Church;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\Facades\URL;

trait RefreshDatabaseWithTenant
{
    use RefreshDatabase {
        beginDatabaseTransaction as parentBeginDatabaseTransaction;
    }

    protected string $tenantId = 'test';

    protected string $tenantName = 'Test Tenant Name';

    /**
     * The database connections that should have transactions.
     *
     * `null` is the default landlord connection, used for system-wide operations.
     * `tenant` is the tenant connection, specific to each tenant in the multi-tenant system.
     */
    protected array $connectionsToTransact = [null, 'tenant'];

    /**
     * We need to hook initialize tenancy _before_ we start the database
     * transaction, otherwise it cannot find the tenant connection.
     * This function initializes the tenant setup before starting a transaction.
     */
    public function beginDatabaseTransaction(): void
    {
        // Initialize tenant before beginning the database transaction.
        $this->initializeTenant();

        // Continue with the default database transaction setup.
        $this->parentBeginDatabaseTransaction();
    }

    /**
     * Initialize tenant for testing environment.
     * This function sets up a specific tenant for testing purposes.
     */
    public function initializeTenant(): void
    {
        // Retrieve or create the tenant with the given ID.
        $tenant = Church::firstOr(function () {
            // Hardcoded tenant ID for testing purposes.
            $tenantId = $this->tenantId;

            /**
             * Set the tenant prefix to the parallel testing token.
             * This is necessary to avoid database collisions when running tests in parallel.
             */
            $token = ParallelTesting::token();
            $tenantId .= $token ? "_{$token}" : '';

            config([
                'tenancy.database.suffix' => config('tenancy.database.suffix').($token ? "_{$token}" : ''),
                'tenancy.filesystem.suffix_base' => config('tenancy.filesystem.suffix_base').($token ? "{$token}_" : ''),
                'tenancy.filesystem.url_override.public' => config('tenancy.filesystem.url_override.public').($token ? "-{$token}" : ''),
            ]);

            // Define the database name for the tenant.
            $dbName = config('tenancy.database.prefix').$tenantId.config('tenancy.database.suffix');

            // Drop the database if it already exists.
            if (DB::getDriverName() === 'sqlite') {
                // For SQLite, we delete the file directly.
                $dbPath = database_path("{$dbName}.sqlite");
                if (File::exists($dbPath)) {
                    File::delete($dbPath);
                }
            } else {
                // For MySQL or other databases, we drop the database.
                DB::unprepared("DROP DATABASE IF EXISTS `$dbName`");
            }

            $storagePath = storage_path(config('tenancy.filesystem.suffix_base')."{$tenantId}");
            File::deleteDirectory(storage_path($storagePath));
            File::deleteDirectory(public_path("public-{$tenantId}".($token ? "-{$token}" : '')));

            // Create the tenant and associated domain if they don't exist.
            $t = Church::create(['id' => $tenantId, 'name' => $this->tenantName, 'locale' => LanguageCode::ENGLISH->value, 'active' => true]);
            if ($t->domains()->doesntExist()) {
                $t->createDomain($tenantId);
            }

            return $t;
        });

        // Initialize tenancy for the current test.
        tenancy()->initialize($tenant);

        // Get the app url that is on the .env file.
        $appUrl = str(env('APP_URL'))->after('://')->before('/')->toString();
        $http = str(env('APP_URL'))->before('://')->toString();

        // Set the root URL for the current tenant.
        URL::forceRootUrl("{$http}://{$tenant->id}.{$appUrl}");
    }
}
