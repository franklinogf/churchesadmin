<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\BaptismCertificateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read string $book
 * @property-read string $folio
 * @property-read string $record_number
 * @property-read string $baptized_name
 * @property-read CarbonImmutable|null $baptized_at
 * @property-read string|null $priest
 * @property-read string|null $birth_place
 * @property-read CarbonImmutable|null $birth_date
 * @property-read string|null $father_name
 * @property-read string|null $father_origin_place
 * @property-read string|null $father_residence_place
 * @property-read string|null $mother_name
 * @property-read string|null $mother_origin_place
 * @property-read string|null $mother_residence_place
 * @property-read string|null $paternal_grandfather_name
 * @property-read string|null $paternal_grandmother_name
 * @property-read string|null $maternal_grandfather_name
 * @property-read string|null $maternal_grandmother_name
 * @property-read string|null $godfather_name
 * @property-read string|null $godmother_name
 * @property-read string|null $issued_place
 * @property-read CarbonImmutable|null $issued_at
 * @property-read string|null $marginal_note
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 */
final class BaptismCertificate extends Model
{
    /** @use HasFactory<BaptismCertificateFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'baptized_at' => 'immutable_date',
            'birth_date' => 'immutable_date',
            'issued_at' => 'immutable_date',
        ];
    }
}
