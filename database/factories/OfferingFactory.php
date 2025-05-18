<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\TransactionMetaType;
use App\Models\ChurchWallet;
use App\Models\Member;
use App\Models\Missionary;
use App\Models\OfferingType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offering>
 */
final class OfferingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $offeringType = fake()->randomElement([Missionary::factory(), OfferingType::factory()])->create();

        return [
            'donor_id' => Member::factory(),
            'transaction_id' => ChurchWallet::factory()->create()->depositFloat(fake()->randomFloat(2, 1, 100), ['type' => TransactionMetaType::OFFERING->value])->id,
            'date' => fake()->date(),
            'payment_method' => fake()->randomElement(PaymentMethod::cases())->value,
            'offering_type_type' => $offeringType->getMorphClass(),
            'offering_type_id' => $offeringType->id,
            'note' => fake()->optional()->sentence(),

        ];
    }

    public function withAmount(float $amount): static
    {
        return $this->state(function (array $attributes) use ($amount) {
            return [
                'transaction_id' => ChurchWallet::factory()->create()->depositFloat($amount, ['type' => TransactionMetaType::OFFERING->value])->id,
            ];
        });
    }

    public function withOfferingType(Missionary|OfferingType $model): static
    {
        return $this->state(function (array $attributes) use ($model) {
            return [
                'offering_type_type' => $model->getMorphClass(),
                'offering_type_id' => $model->getKey(),
            ];
        });
    }
}
