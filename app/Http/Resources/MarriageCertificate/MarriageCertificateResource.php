<?php

declare(strict_types=1);

namespace App\Http\Resources\MarriageCertificate;

use App\Models\MarriageCertificate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/**
 * @mixin MarriageCertificate
 */
final class MarriageCertificateResource extends JsonResource
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
            'marriedAt' => $this->married_at?->format('Y-m-d'),
            'priest' => $this->priest,
            'groomName' => $this->groom_name,
            'groomAge' => $this->groom_age,
            'groomBirthplace' => $this->groom_birthplace,
            'groomResidence' => $this->groom_residence,
            'groomFatherName' => $this->groom_father_name,
            'groomMotherName' => $this->groom_mother_name,
            'brideName' => $this->bride_name,
            'brideAge' => $this->bride_age,
            'brideBirthplace' => $this->bride_birthplace,
            'brideResidence' => $this->bride_residence,
            'brideFatherName' => $this->bride_father_name,
            'brideMotherName' => $this->bride_mother_name,
            'witness1Name' => $this->witness1_name,
            'witness2Name' => $this->witness2_name,
            'issuedAt' => $this->issued_at?->format('Y-m-d'),
            'marginalNote' => $this->marginal_note,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
