<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ConfirmationCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ConfirmationCertificate> */
final class ConfirmationCertificateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'book' => fake()->numerify('C-###'),
            'folio' => fake()->numerify('F-###'),
            'record_number' => fake()->numerify('N-###'),
            'priest' => fake()->name(),
            'confirmed_name' => fake()->name(),
            'father_name' => fake()->name('male'),
            'mother_name' => fake()->name('female'),
            'confirmed_by' => fake()->name(),
            'confirmed_at' => fake()->date(),
            'godfather_name' => fake()->name('male'),
            'godmother_name' => fake()->name('female'),
            'issued_place' => fake()->city(),
            'issued_at' => fake()->date(),
            'marginal_note' => fake()->optional()->sentence(),
        ];
    }
}
