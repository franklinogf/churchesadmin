<?php

declare(strict_types=1);

use App\Models\Church;
use App\Models\TenantUser;
use Bavix\Wallet\Services\FormatterServiceInterface;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

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
            $domain = $church->domain;

            $url = config()->string('app.url');

            return tenant_route($domain.'.'.str($url)->after('://'), $routeName, $routeParams);
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

if (! function_exists('format_to_currency')) {
    /**
     * Format a integer to decimal into currency format.
     */
    function format_to_currency(int|string $amount, string $currency = 'USD'): string
    {
        $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        $floatValue = format_to_float($amount);

        return $formatter->formatCurrency($floatValue, $currency);
    }
}

if (! function_exists('format_to_float')) {
    /**
     * Format a integer to decimal float.
     */
    function format_to_float(int|string $amount, int $decimals = 2): float
    {
        return (float) app(FormatterServiceInterface::class)->floatValue($amount, $decimals);
    }
}

if (! function_exists('display_date')) {
    /**
     * Display a date in the application's display timezone.
     */
    function display_date(?CarbonInterface $date, string $format = 'Y-m-d H:i:s'): ?string
    {
        if (! $date instanceof CarbonInterface) {
            return null;
        }

        return $date
            ->timezone(config('app.timezone_display'))
            ->format($format);
    }
}
