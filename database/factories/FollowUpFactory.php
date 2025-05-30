<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\FollowUpType;
use App\Models\Member;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FollowUp>
 */
final class FollowUpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'visit_id' => Visit::factory(),
            'member_id' => Member::factory(),
            'type' => fake()->randomElement(FollowUpType::cases()),
            'follow_up_at' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'notes' => fake()->optional(0.8)->text(200),
        ];
    }
}
