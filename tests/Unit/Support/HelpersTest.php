<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Models\Church;
use App\Models\TenantUser;
use Bavix\Wallet\Services\FormatterService;
use Bavix\Wallet\Services\FormatterServiceInterface;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

beforeEach(function (): void {
    config()->set('app.url', 'https://central.test');
    config()->set('app.timezone', 'UTC');
    app()->instance(FormatterServiceInterface::class, new FormatterService());
});

describe('serverDate helper', function (): void {
    it('converts carbon immutable instances to the server timezone', function (): void {
        $input = CarbonImmutable::parse('2024-01-01 10:00:00', 'America/New_York');

        $result = serverDate($input);

        expect($result->timezoneName)->toBe('UTC')
            ->and($result->format('Y-m-d H:i'))->toBe('2024-01-01 15:00');
    });

    it('parses strings according to the authenticated tenant user timezone', function (): void {
        $user = new TenantUser();
        $user->forceFill([
            'id' => (string) Str::uuid(),
            'name' => 'Test User',
            'email' => 'test@example.com',
            'timezone' => 'America/Los_Angeles',
        ]);

        $guard = Auth::guard('tenant');
        $guard->setUser($user);

        $result = serverDate('2024-01-01 05:00:00');

        expect($result->timezoneName)->toBe('UTC')
            ->and($result->format('Y-m-d H:i'))->toBe('2024-01-01 13:00');

        Auth::forgetGuards();
    });
});

describe('create_tenant_url helper', function (): void {
    it('builds tenant-aware urls for named routes', function (): void {
        $church = Church::make([
            'id' => 'demo-church',
            'domain' => 'demo',
        ]);

        $url = create_tenant_url($church, 'dashboard');

        expect($url)->toBe('https://demo.central.test/dashboard');
    });

    it('includes route parameters when generating the url', function (): void {
        $church = Church::make([
            'id' => 'demo-church',
            'domain' => 'demo',
        ]);

        $url = create_tenant_url($church, 'impersonate', ['token' => 'abc123']);

        expect($url)->toBe('https://demo.central.test/impersonate/abc123');
    });

    it('returns null when the church is not provided', function (): void {
        expect(create_tenant_url(null, 'dashboard'))->toBeNull();
    });

    it('returns null when the route cannot be generated', function (): void {
        $church = Church::make([
            'id' => 'demo-church',
            'domain' => 'demo',
        ]);

        expect(create_tenant_url($church, 'helpers.missing'))->toBeNull();
    });
});

describe('app_url_subdomain helper', function (): void {
    it('creates fully qualified urls using the provided subdomain', function (): void {
        $url = app_url_subdomain('reports');

        expect($url)->toBe('https://reports.central.test');
    });
});

describe('format_to_float helper', function (): void {
    it('converts integer values into decimal floats', function (): void {
        $result = format_to_float(12345, 2);

        expect($result)->toBe(123.45)
            ->and($result)->toBeFloat();
    });

    it('handles string inputs gracefully', function (): void {
        $result = format_to_float('999', 3);

        expect($result)->toBe(0.999)
            ->and($result)->toBeFloat();
    });
});

describe('format_to_currency helper', function (): void {
    it('formats values into localized currency strings', function (): void {
        $currency = format_to_currency(12345, 'USD');

        expect($currency)->toBe('$123.45');
    });
});
