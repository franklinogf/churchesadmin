<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * CurrentYear model.
 *
 * @property-read int $id
 * @property-read int $year
 * @property-read CarbonImmutable|null $start_date
 * @property-read CarbonImmutable|null $end_date
 * @property-read bool $is_current
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 */
final class CurrentYear extends Model
{
    /** @use HasFactory<\Database\Factories\CurrentYearFactory> */
    use HasFactory;

    public static function current(): static
    {
        return self::query()
            ->where('is_current', true)
            ->firstOrFail();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_current' => 'boolean',
            'start_date' => 'immutable_date',
            'end_date' => 'immutable_date',
        ];
    }
}
