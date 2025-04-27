<?php

declare(strict_types=1);

namespace Database\Seeders\Tenants;

use App\Enums\TenantRole;
use App\Models\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'email_verified_at' => now(),
            'password' => 'Password123',
        ]);

        $user->assignRole(TenantRole::SUPER_ADMIN->value);
    }
}
