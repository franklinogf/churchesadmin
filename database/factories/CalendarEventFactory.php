<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CalendarEventColorEnum;
use App\Models\TenantUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CalendarEvent>
 */
final class CalendarEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startAt = fake()->dateTimeBetween('now', '+1 month');
        $endAt = (clone $startAt)->modify('+'.fake()->numberBetween(1, 4).' hours');

        return [
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'location' => fake()->optional()->address(),
            'color' => fake()->randomElement(CalendarEventColorEnum::cases()),
            'start_at' => $startAt,
            'end_at' => $endAt,
            'created_by' => TenantUser::factory(),
        ];
    }
}
