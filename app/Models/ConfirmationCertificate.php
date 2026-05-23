<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\ConfirmationCertificateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read string $book
 * @property-read string $folio
 * @property-read string $record_number
 * @property-read string|null $priest
 * @property-read string $confirmed_name
 * @property-read string|null $father_name
 * @property-read string|null $mother_name
 * @property-read string|null $confirmed_by
 * @property-read CarbonImmutable|null $confirmed_at
 * @property-read string|null $godfather_name
 * @property-read string|null $godmother_name
 * @property-read string|null $issued_place
 * @property-read CarbonImmutable|null $issued_at
 * @property-read string|null $marginal_note
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 */
final class ConfirmationCertificate extends Model
{
    /** @use HasFactory<ConfirmationCertificateFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'confirmed_at' => 'immutable_date',
            'issued_at' => 'immutable_date',
        ];
    }
}
