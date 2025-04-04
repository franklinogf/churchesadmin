<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Missionary model.
 *
 * @property-read int $id
 * @property-read string $name
 * @property-read string $last_name
 * @property-read string $email
 * @property-read string|null $phone
 * @property-read \App\Enums\Gender $gender
 * @property-read string $church
 * @property-read float|null $offering
 * @property-read \App\Enums\OfferingFrequency|null $offering_frequency
 * @property-read \Carbon\CarbonImmutable|null $deleted_at
 * @property-read \Carbon\CarbonImmutable|null $created_at
 * @property-read \Carbon\CarbonImmutable|null $updated_at
 */
final class Missionary extends Model
{
    /** @use HasFactory<\Database\Factories\MissionaryFactory> */
    use HasFactory;

    /**
     * Get the address of this model.
     *
     * @return MorphOne<Address, Missionary>
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gender' => \App\Enums\Gender::class,
            'offering_frequency' => \App\Enums\OfferingFrequency::class,
        ];
    }
}
