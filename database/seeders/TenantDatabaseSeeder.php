<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\TagType;
use App\Models\Address;
use App\Models\Member;
use App\Models\Missionary;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the tenants's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Member::factory(5)->has(Address::factory())->create();

        Missionary::factory(5)->has(Address::factory())->create();

        Tag::create([
            'name' => [
                'en' => 'Regular',
                'es' => 'Regular',
            ],
            'type' => TagType::CATEGORY->value,
            'is_regular' => true,
        ]);

    }
}
