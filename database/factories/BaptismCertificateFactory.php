<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\BaptismCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BaptismCertificate>
 */
final class BaptismCertificateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book' => fake()->unique()->numerify('B-###'),
            'folio' => fake()->numerify('F-###'),
            'record_number' => fake()->unique()->numerify('N-###'),
            'baptized_name' => fake()->name(),
            'baptized_at' => fake()->date(),
            'priest' => fake()->name(),
            'birth_place' => fake()->city(),
            'birth_date' => fake()->date(),
            'father_name' => fake()->name('male'),
            'father_origin_place' => fake()->city(),
            'father_residence_place' => fake()->city(),
            'mother_name' => fake()->name('female'),
            'mother_origin_place' => fake()->city(),
            'mother_residence_place' => fake()->city(),
            'paternal_grandfather_name' => fake()->name('male'),
            'paternal_grandmother_name' => fake()->name('female'),
            'maternal_grandfather_name' => fake()->name('male'),
            'maternal_grandmother_name' => fake()->name('female'),
            'godfather_name' => fake()->name('male'),
            'godmother_name' => fake()->name('female'),
            'issued_place' => fake()->city(),
            'issued_at' => fake()->date(),
            'marginal_note' => fake()->optional()->sentence(),
        ];
    }
}
