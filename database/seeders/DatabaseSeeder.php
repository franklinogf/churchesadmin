<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Profile;
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
        // User::factory(10)->create();

        User::factory()
            ->has(
                Profile::factory()->has(
                    Address::factory()->count(3)->sequence(
                        ['is_primary' => true],
                        ['is_primary' => false],
                        ['is_primary' => false]
                    )
                )
            )->create(['email' => 'test@example.com']);

    }
}
