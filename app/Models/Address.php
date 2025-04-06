<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Address model.
 *
 * @property-read int $id
 * @property-read string $owner_type
 * @property-read int $owner_id
 * @property-read string $address_1
 * @property-read string $address_2
 * @property-read string $city
 * @property-read string $state
 * @property-read string $country
 * @property-read string $postal_code
 * @property-read \Carbon\CarbonImmutable|null $created_at
 * @property-read \Carbon\CarbonImmutable|null $updated_at
 */
final class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    /**
     * Get the address model that this address belongs to.
     *
     * @return MorphTo<Model, Address>
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}
