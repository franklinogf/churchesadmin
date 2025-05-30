<?php

declare(strict_types=1);

namespace Database\Seeders\Tenants;

use App\Models\Visit;
use Illuminate\Database\Seeder;

final class VisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Visit::factory()
            ->count(10)
            ->create();
    }
}
