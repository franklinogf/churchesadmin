<?php

declare(strict_types=1);

namespace App\Services\Session;

use App\Enums\SessionName;

final class SessionService
{
    /**
     * Create a session with the given name and value.
     */
    public static function create(SessionName $name, mixed $value): void
    {
        session()->put($name->value, $value);
    }

    /**
     * Get the session value for the given name.
     */
    public static function get(SessionName $name, mixed $default = null): mixed
    {
        return session()->get($name->value, $default);
    }

    /**
     * Get the session value for the given name and forget it.
     */
    public static function getAndForget(SessionName $name, mixed $default = null): mixed
    {
        $value = self::get($name, $default);
        self::forget($name);

        return $value;
    }

    /**
     * Delete the session with the given name.
     */
    public static function forget(SessionName $name): void
    {
        session()->forget($name->value);
    }
}
