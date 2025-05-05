<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsUcWords;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read string $name
 * @property float|null $default_amount
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
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
    protected function casts(): array
    {

        return [
            'name' => AsUcWords::class,
        ];
    }
}
