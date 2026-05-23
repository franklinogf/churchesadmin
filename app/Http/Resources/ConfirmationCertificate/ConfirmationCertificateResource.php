<?php

declare(strict_types=1);

namespace App\Http\Resources\ConfirmationCertificate;

use App\Models\ConfirmationCertificate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/**
 * @mixin ConfirmationCertificate
 */
final class ConfirmationCertificateResource extends JsonResource
{
    /**
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
            'priest' => $this->priest,
            'confirmedName' => $this->confirmed_name,
            'fatherName' => $this->father_name,
            'motherName' => $this->mother_name,
            'confirmedBy' => $this->confirmed_by,
            'confirmedAt' => $this->confirmed_at?->format('Y-m-d'),
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
