<?php

declare(strict_types=1);

use App\Models\Church;
use App\Models\TenantUser;
use Carbon\CarbonImmutable;

if (! function_exists('serverDate')) {
    /**
     * Convert a date to the server's UTC timezone.
     */
    function serverDate(CarbonImmutable|string $date): CarbonImmutable
    {
        $serverTimezone = config()->string('app.timezone');

        if (! $date instanceof CarbonImmutable) {
            /**
             * @var TenantUser|null
             */
            $currentUser = auth('tenant')->user();
            $date = CarbonImmutable::parse($date, $currentUser->timezone ?? $serverTimezone);
        }

        $date = $date->setTimezone($serverTimezone);

        return $date;
    }
}

if (! function_exists('create_tenant_url')) {
    /**
     * Create a URL for a tenant.
     */
    function create_tenant_url(?Church $church, string $routeName): ?string
    {
        if ($church === null) {
            return null;
        }

        return tenant_route($church->domains()->first()->domain.'.'.str(config('app.url'))->after('://'), $routeName);
    }
}
