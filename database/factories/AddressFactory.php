<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Member;
use App\Models\Missionary;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
final class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $owner = fake()->randomElement([Member::factory()->create(), Missionary::factory()->create()]);

        return [
            'owner_id' => $owner,
            'owner_type' => $owner->getMorphClass(),
            'address_1' => fake()->streetAddress(),
            'address_2' => fake()->secondaryAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zip_code' => fake()->postcode(),
            'country' => fake()->country(),
        ];
    }

    public function forMember(): Factory
    {
        return $this->state(function (array $attributes): array {
            $member = Member::factory()->create();

            return [
                'owner_id' => $member,
                'owner_type' => $member->getMorphClass(),
            ];
        });
    }

    public function forMissionary(): Factory
    {
        return $this->state(function (array $attributes): array {
            $missionary = Missionary::factory()->create();

            return [
                'owner_id' => $missionary,
                'owner_type' => $missionary->getMorphClass(),
            ];
        });
    }
}
