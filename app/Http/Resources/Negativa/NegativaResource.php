<?php

declare(strict_types=1);

namespace App\Http\Resources\Negativa;

use App\Models\Negativa;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/**
 * @mixin Negativa
 */
final class NegativaResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'personName' => $this->person_name,
            'fatherName' => $this->father_name,
            'motherName' => $this->mother_name,
            'searchedFrom' => $this->searched_from?->format('Y-m-d'),
            'searchedTo' => $this->searched_to?->format('Y-m-d'),
            'priest' => $this->priest,
            'issuedAt' => $this->issued_at?->format('Y-m-d'),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
