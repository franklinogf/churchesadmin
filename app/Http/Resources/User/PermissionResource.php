<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Enums\TenantPermission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;
use Spatie\Permission\Models\Permission;

/**
 * @mixin Permission
 */
final class PermissionResource extends JsonResource
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
            'name' => $this->name,
            'label' => TenantPermission::from($this->name)->label(),
        ];
    }
}
