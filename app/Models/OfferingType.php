<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 * @property-read Offering[] $offerings
 */
final class OfferingType extends Model
{
    /** @use HasFactory<\Database\Factories\OfferingTypeFactory> */
    use HasFactory;

    /**
     * Get the offerings of this model.
     *
     * @return MorphMany<Offering, $this>
     */
    public function offerings(): MorphMany
    {
        return $this->morphMany(Offering::class, 'offering_type');
    }
}
