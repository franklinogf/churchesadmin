<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\FirstCommunionCertificateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read string|null $priest
 * @property-read string $communicant_name
 * @property-read string|null $father_name
 * @property-read string|null $mother_name
 * @property-read CarbonImmutable|null $communion_at
 * @property-read CarbonImmutable|null $issued_at
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 */
final class FirstCommunionCertificate extends Model
{
    /** @use HasFactory<FirstCommunionCertificateFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'communion_at' => 'immutable_date',
            'issued_at' => 'immutable_date',
        ];
    }
}
