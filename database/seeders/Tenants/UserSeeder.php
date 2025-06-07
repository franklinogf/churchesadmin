<?php

declare(strict_types=1);

namespace Database\Seeders\Tenants;

use App\Enums\TenantRole;
use App\Models\CurrentYear;
use App\Models\TenantUser;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = TenantUser::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'email_verified_at' => now(),
            'password' => 'Password123',
            'current_year_id' => CurrentYear::first()?->id ?? 1,
        ]);

        $user->assignRole(TenantRole::SUPER_ADMIN->value);
    }
}
