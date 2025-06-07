<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TenantRole;
use App\Models\CurrentYear;
use App\Models\TenantUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TenantUser>
 */
final class TenantUserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => self::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'timezone' => 'America/New_York',
            'timezone_country' => 'US',
            'current_year_id' => CurrentYear::first()?->id ?? CurrentYear::factory(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function superAdmin(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
            ];
        })->afterCreating(function (TenantUser $user) {
            $user->assignRole(TenantRole::SUPER_ADMIN);
        });
    }

    public function admin(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Admin',
                'email' => 'admin@example.com',
            ];
        })->afterCreating(function (TenantUser $user) {
            $user->assignRole(TenantRole::ADMIN);
        });
    }

    public function secretary(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Secretary',
                'email' => 'secretary@example.com',
            ];
        })->afterCreating(function (TenantUser $user) {
            $user->assignRole(TenantRole::SECRETARY);
        });
    }

    public function noRole(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'No Role',
                'email' => 'norole@example.com',
            ];
        })->afterCreating(function (TenantUser $user) {
            $user->assignRole(TenantRole::NO_ROLE);
        });
    }
}
