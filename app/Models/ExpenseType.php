<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsUcWords;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read string $name
 * @property float|null $default_amount
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 */
final class ExpenseType extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseTypeFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {

        return [
            'name' => AsUcWords::class,
        ];
    }
}
