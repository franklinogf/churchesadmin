<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\MarriageCertificateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read string $book
 * @property-read string $folio
 * @property-read string $record_number
 * @property-read CarbonImmutable|null $married_at
 * @property-read string|null $priest
 * @property-read string $groom_name
 * @property-read string|null $groom_age
 * @property-read string|null $groom_birthplace
 * @property-read string|null $groom_residence
 * @property-read string|null $groom_father_name
 * @property-read string|null $groom_mother_name
 * @property-read string $bride_name
 * @property-read string|null $bride_age
 * @property-read string|null $bride_birthplace
 * @property-read string|null $bride_residence
 * @property-read string|null $bride_father_name
 * @property-read string|null $bride_mother_name
 * @property-read string|null $witness1_name
 * @property-read string|null $witness2_name
 * @property-read CarbonImmutable|null $issued_at
 * @property-read string|null $marginal_note
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 */
final class MarriageCertificate extends Model
{
    /** @use HasFactory<MarriageCertificateFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'married_at' => 'immutable_date',
            'issued_at' => 'immutable_date',
        ];
    }
}
