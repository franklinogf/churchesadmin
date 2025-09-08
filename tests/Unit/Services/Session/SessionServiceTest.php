<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Session;

use App\Enums\SessionName;
use App\Services\Session\SessionService;
use Illuminate\Support\Facades\Session;

describe('SessionService', function (): void {
    beforeEach(function (): void {
        Session::shouldReceive('driver')->andReturnSelf();
    });

    it('can create a session value', function (): void {
        // Arrange
        $name = SessionName::EMAIL_RECIPIENTS;
        $value = ['test@example.com', 'another@example.com'];

        // Expect
        Session::shouldReceive('put')
            ->once()
            ->with($name->value, $value);

        // Act
        SessionService::create($name, $value);
    });

    it('can get a session value', function (): void {
        // Arrange
        $name = SessionName::EMAIL_RECIPIENTS;
        $value = ['test@example.com', 'another@example.com'];

        // Expect
        Session::shouldReceive('get')
            ->once()
            ->with($name->value, null)
            ->andReturn($value);

        // Act
        $result = SessionService::get($name);

        // Assert
        expect($result)->toBe($value);
    });

    it('can get a session value with a default', function (): void {
        // Arrange
        $name = SessionName::EMAIL_RECIPIENTS;
        $default = ['default@example.com'];

        // Expect
        Session::shouldReceive('get')
            ->once()
            ->with($name->value, $default)
            ->andReturn($default);

        // Act
        $result = SessionService::get($name, $default);

        // Assert
        expect($result)->toBe($default);
    });

    it('can forget a session value', function (): void {
        // Arrange
        $name = SessionName::EMAIL_RECIPIENTS;

        // Expect
        Session::shouldReceive('forget')
            ->once()
            ->with($name->value);

        // Act
        SessionService::forget($name);
    });

    it('can get and forget a session value', function (): void {
        // Arrange
        $name = SessionName::EMAIL_RECIPIENTS;
        $value = ['test@example.com'];

        // Expect
        Session::shouldReceive('get')
            ->once()
            ->with($name->value, null)
            ->andReturn($value);

        Session::shouldReceive('forget')
            ->once()
            ->with($name->value);

        // Act
        $result = SessionService::getAndForget($name);

        // Assert
        expect($result)->toBe($value);
    });

    it('can get and forget a session value with default', function (): void {
        // Arrange
        $name = SessionName::EMAIL_RECIPIENTS;
        $default = ['default@example.com'];

        // Expect
        Session::shouldReceive('get')
            ->once()
            ->with($name->value, $default)
            ->andReturn($default);

        Session::shouldReceive('forget')
            ->once()
            ->with($name->value);

        // Act
        $result = SessionService::getAndForget($name, $default);

        // Assert
        expect($result)->toBe($default);
    });
});
