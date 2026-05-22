<?php

declare(strict_types=1);

namespace App\Http\Resources\BaptismCertificate;

use App\Models\BaptismCertificate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/**
 * @mixin BaptismCertificate
 */
final class BaptismCertificateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'book' => $this->book,
            'folio' => $this->folio,
            'recordNumber' => $this->record_number,
            'baptizedName' => $this->baptized_name,
            'baptizedAt' => $this->baptized_at?->format('Y-m-d'),
            'priest' => $this->priest,
            'birthPlace' => $this->birth_place,
            'birthDate' => $this->birth_date?->format('Y-m-d'),
            'fatherName' => $this->father_name,
            'fatherOriginPlace' => $this->father_origin_place,
            'fatherResidencePlace' => $this->father_residence_place,
            'motherName' => $this->mother_name,
            'motherOriginPlace' => $this->mother_origin_place,
            'motherResidencePlace' => $this->mother_residence_place,
            'paternalGrandfatherName' => $this->paternal_grandfather_name,
            'paternalGrandmotherName' => $this->paternal_grandmother_name,
            'maternalGrandfatherName' => $this->maternal_grandfather_name,
            'maternalGrandmotherName' => $this->maternal_grandmother_name,
            'godfatherName' => $this->godfather_name,
            'godmotherName' => $this->godmother_name,
            'issuedPlace' => $this->issued_place,
            'issuedAt' => $this->issued_at?->format('Y-m-d'),
            'marginalNote' => $this->marginal_note,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
