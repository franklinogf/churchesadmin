<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 */
final class OfferingType extends Model
{
    /** @use HasFactory<\Database\Factories\OfferingTypeFactory> */
    use HasFactory, HasTranslations;

    /**
     * The attributes that should be translatable.
     *
     * @var array<string>
     */
    protected $translatable = ['name'];
}
