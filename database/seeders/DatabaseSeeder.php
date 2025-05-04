<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\LanguageCode;
use App\Models\Church;
use App\Models\TenantUser;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        TenantUser::create([
            'name' => 'Test user',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => 'password',
        ]);

        if (! app()->isProduction()) {
            Church::create([
                'id' => 'test-church',
                'name' => 'Test Church',
                'locale' => LanguageCode::ENGLISH,
                'active' => true,
            ])->createDomain('test');
        }

    }
}
