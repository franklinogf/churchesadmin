<?php

declare(strict_types=1);

namespace Database\Seeders\Tenants;

use App\Models\OfferingType;
use Illuminate\Database\Seeder;

final class OfferingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OfferingType::factory(5)->create();
    }
}
