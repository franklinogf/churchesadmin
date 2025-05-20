<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LanguageCode;
use App\Enums\MediaCollectionName;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Interfaces\WalletFloat;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Traits\HasWallets;
use DateTimeInterface;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
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
 * @property-read string $locale
 * @property-read bool $active
 * @property-read array<string,mixed>|null $data
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 */
final class Church extends BaseTenant implements HasMedia, TenantWithDatabase, Wallet, WalletFloat
{
    use HasDatabase, HasDomains, HasWalletFloat, HasWallets, InteractsWithMedia, MaintenanceMode;

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
            'locale',
            'active',
        ]);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionName::LOGO->value)
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/svg+xml']);
    }

    protected function casts(): array
    {
        return [
            ...parent::casts(),
            // 'locale' => LanguageCode::class,
            'active' => 'boolean',
        ];
    }
}
