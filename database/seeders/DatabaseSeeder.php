<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\LanguageCode;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Test user',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => 'password',
            'language' => LanguageCode::EN->value,
        ]);

    }
}
