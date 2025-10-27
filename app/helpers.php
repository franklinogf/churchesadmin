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
     *
     * @param  array<string, mixed>  $routeParams
     */
    function create_tenant_url(?Church $church, string $routeName, array $routeParams = []): ?string
    {
        if (! $church instanceof Church) {
            return null;
        }

        try {

            $domain = $church->domains()->first();
            if (! $domain) {
                return null;
            }

            $url = config()->string('app.url');

            return tenant_route($domain->domain.'.'.str($url)->after('://'), $routeName, $routeParams);
        } catch (Exception) {
            return null;
        }
    }
}

if (! function_exists('app_url_subdomain')) {
    /**
     * Get the application URL.
     */
    function app_url_subdomain(string $subdomain): string
    {
        $url = config()->string('app.url');

        return str($url)->before('://').'://'.$subdomain.'.'.str($url)->after('://');
    }
}
