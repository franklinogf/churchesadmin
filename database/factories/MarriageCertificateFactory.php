<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MarriageCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<MarriageCertificate> */
final class MarriageCertificateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'book' => fake()->numerify('M-###'),
            'folio' => fake()->numerify('F-###'),
            'record_number' => fake()->numerify('N-###'),
            'married_at' => fake()->date(),
            'priest' => fake()->name(),
            'groom_name' => fake()->name('male'),
            'groom_age' => (string) fake()->numberBetween(18, 70),
            'groom_birthplace' => fake()->city(),
            'groom_residence' => fake()->city(),
            'groom_father_name' => fake()->name('male'),
            'groom_mother_name' => fake()->name('female'),
            'bride_name' => fake()->name('female'),
            'bride_age' => (string) fake()->numberBetween(18, 70),
            'bride_birthplace' => fake()->city(),
            'bride_residence' => fake()->city(),
            'bride_father_name' => fake()->name('male'),
            'bride_mother_name' => fake()->name('female'),
            'witness1_name' => fake()->name(),
            'witness2_name' => fake()->name(),
            'issued_at' => fake()->date(),
            'marginal_note' => fake()->optional()->sentence(),
        ];
    }
}
