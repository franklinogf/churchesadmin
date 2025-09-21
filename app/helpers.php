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
        if (! $church instanceof Church) {
            return null;
        }

        try {
            /** @phpstan-ignore-next-line */
            $domain = $church->domains()->first();
            if (! $domain) {
                return null;
            }

            $appUrl = config('app.url');
            if (! is_string($appUrl)) {
                return null;
            }

            /** @phpstan-ignore-next-line */
            return tenant_route($domain->domain.'.'.str($appUrl)->after('://'), $routeName);
        } catch (Exception) {
            return null;
        }
    }
}
