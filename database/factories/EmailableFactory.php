<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\EmailStatus;
use App\Models\Email;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Emailable>
 */
final class EmailableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $recipient = Member::factory()->create();

        return [
            'email_id' => Email::factory(),
            'recipient_id' => $recipient->id,
            'recipient_type' => $recipient->getMorphClass(),
            'status' => fake()->randomElement(EmailStatus::cases()),
            'sent_at' => fake()->optional()->dateTime(),
            'error_message' => fake()->optional()->sentence(),
        ];
    }

    public function sent(): static
    {
        return $this->state([
            'status' => EmailStatus::SENT,
            'sent_at' => fake()->dateTime(),
        ]);
    }

    public function failed(): static
    {
        return $this->state([
            'status' => EmailStatus::FAILED,
            'error_message' => fake()->sentence(),
        ]);
    }
}
