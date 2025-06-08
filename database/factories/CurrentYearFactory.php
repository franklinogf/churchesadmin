<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CurrentYear>
 */
final class CurrentYearFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = fake()->year();

        return [
            'year' => $year,
            'start_date' => "{$year}-01-01",
            'end_date' => "{$year}-12-31",
            'is_current' => false,
        ];
    }
}
