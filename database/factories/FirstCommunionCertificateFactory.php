<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\FirstCommunionCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<FirstCommunionCertificate> */
final class FirstCommunionCertificateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'priest' => fake()->name(),
            'communicant_name' => fake()->name(),
            'father_name' => fake()->name('male'),
            'mother_name' => fake()->name('female'),
            'communion_at' => fake()->date(),
            'issued_at' => fake()->date(),
        ];
    }
}
