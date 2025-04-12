<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use App\Enums\OfferingFrequency;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Missionary model.
 *
 * @property-read int $id
 * @property-read string $name
 * @property-read string $last_name
 * @property-read string $email
 * @property-read string $phone
 * @property-read Gender $gender
 * @property-read string $church
 * @property-read float $offering
 * @property-read OfferingFrequency $offering_frequency
 * @property-read CarbonImmutable|null $deleted_at
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 * @property-read Address|null $address
 */
final class Missionary extends Model
{
    /** @use HasFactory<\Database\Factories\MissionaryFactory> */
    use HasFactory,SoftDeletes;

    /**
     * Get the address of this model.
     *
     * @return MorphOne<Address, $this>
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'owner');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
            'offering_frequency' => OfferingFrequency::class,
        ];
    }
}
