<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TenantUser;
use DateTimeImmutable;
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
            'start_at' => $startAt,
            'end_at' => $endAt,
            'created_by' => TenantUser::factory(),
        ];
    }

    /**
     * Indicate that the event is an all-day event.
     */
    public function allDay(): static
    {
        return $this->state(function (array $attributes) {
            $startAt = new DateTimeImmutable($attributes['start_at']);
            $endAt = new DateTimeImmutable($attributes['end_at']);

            // Set time to 00:00:00 for all-day events
            $startAt->setTime(0, 0, 0);
            $endAt->setTime(0, 0, 0);

            return [
                'start_at' => $startAt,
                'end_at' => $endAt,
            ];
        });
    }
}
