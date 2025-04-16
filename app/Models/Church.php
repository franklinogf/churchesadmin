<?php

declare(strict_types=1);

namespace App\Models;

use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Concerns\MaintenanceMode;
use Stancl\Tenancy\Database\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

/**
 * Tenant model.
 *
 * @property-read string $id
 * @property-read string $name
 * @property-read array<string,mixed>|null $data
 * @property-read \Carbon\CarbonImmutable|null $created_at
 * @property-read \Carbon\CarbonImmutable|null $updated_at
 */
final class Church extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, MaintenanceMode;

    /**
     * Set the custom columns for the tenant model.
     *
     * @return array<int,string>
     */
    public static function getCustomColumns(): array
    {
        /** @var array<int, string> $parentColumns */
        $parentColumns = parent::getCustomColumns();

        return array_merge($parentColumns, [
            'name',
        ]);
    }
}
