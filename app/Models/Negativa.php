<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\NegativaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read string $person_name
 * @property-read string|null $father_name
 * @property-read string|null $mother_name
 * @property-read CarbonImmutable|null $searched_from
 * @property-read CarbonImmutable|null $searched_to
 * @property-read string|null $priest
 * @property-read CarbonImmutable|null $issued_at
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 */
final class Negativa extends Model
{
    /** @use HasFactory<NegativaFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'searched_from' => 'immutable_date',
            'searched_to' => 'immutable_date',
            'issued_at' => 'immutable_date',
        ];
    }
}
