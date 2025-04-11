<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Enums\TenantRole;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'label' => TenantRole::tryFrom($this->name)?->label(),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
        ];
    }
}
