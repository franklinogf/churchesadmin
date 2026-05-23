<?php

declare(strict_types=1);

namespace App\Http\Resources\FirstCommunionCertificate;

use App\Models\FirstCommunionCertificate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/**
 * @mixin FirstCommunionCertificate
 */
final class FirstCommunionCertificateResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'priest' => $this->priest,
            'communicantName' => $this->communicant_name,
            'fatherName' => $this->father_name,
            'motherName' => $this->mother_name,
            'communionAt' => $this->communion_at?->format('Y-m-d'),
            'issuedAt' => $this->issued_at?->format('Y-m-d'),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
