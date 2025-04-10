<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'address1' => $this->address_1,
            'address2' => $this->address_2,
            'city' => $this->city,
            'state' => $this->state,
            'zipCode' => $this->zip_code,
            'country' => $this->country,
        ];
    }
}
