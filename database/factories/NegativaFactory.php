<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Negativa;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Negativa> */
final class NegativaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'person_name' => fake()->name(),
            'father_name' => fake()->name('male'),
            'mother_name' => fake()->name('female'),
            'searched_from' => fake()->date(),
            'searched_to' => fake()->date(),
            'priest' => fake()->name(),
            'issued_at' => fake()->date(),
        ];
    }
}
