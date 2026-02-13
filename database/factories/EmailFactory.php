<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\EmailStatus;
use App\Enums\ModelMorphName;
use App\Models\TenantUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Email>
 */
final class EmailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject' => fake()->sentence(),
            'body' => fake()->paragraph(),
            'sender_id' => TenantUser::factory(),
            'recipients_type' => fake()->randomElement(ModelMorphName::cases()),
            'reply_to' => fake()->optional()->email(),
            'status' => fake()->randomElement(EmailStatus::cases()),
            'sent_at' => fake()->optional()->dateTime(timezone: 'UTC'),
        ];
    }

    public function sent(): static
    {
        return $this->state([
            'status' => EmailStatus::SENT,
            'sent_at' => fake()->dateTime(timezone: 'UTC'),
        ]);
    }

    public function pending(): static
    {
        return $this->state([
            'status' => EmailStatus::PENDING,
            'sent_at' => null,
        ]);
    }
}
