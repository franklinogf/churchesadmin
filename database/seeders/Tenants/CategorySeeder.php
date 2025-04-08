<?php

declare(strict_types=1);

namespace Database\Seeders\Tenants;

use App\Enums\TagType;
use App\Models\Tag;
use Illuminate\Database\Seeder;

final class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
