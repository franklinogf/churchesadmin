<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MediaCollectionName;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Interfaces\WalletFloat;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Traits\HasWallets;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Pennant\Concerns\HasFeatures;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Stancl\Tenancy\Contracts\SingleDomainTenant;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
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
 * @property-read string|null $logo
 * @property-read array<string,mixed>|null $data
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 * @property-read string|null $logoPath
 * @property-read string $domain
 */
final class Church extends BaseTenant implements HasMedia, SingleDomainTenant, TenantWithDatabase, Wallet, WalletFloat
{
    /** @use HasFactory<\Database\Factories\ChurchFactory> */
    use HasDatabase, HasFactory, HasFeatures, HasWalletFloat, HasWallets, InteractsWithMedia, MaintenanceMode;

    /**
     * Set the custom columns for the tenant model.
     *
     * @return array<int,string>
     */
    #[\Override]
    public static function getCustomColumns(): array
    {
        /** @var array<int, string> $parentColumns */
        $parentColumns = parent::getCustomColumns();

        return array_merge($parentColumns, [
            'name',
            'locale',
            'active',
            'domain',
        ]);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionName::LOGO->value)
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/svg+xml']);
    }

    /**
     * Get the church logo.
     *
     * @return Attribute<string|null,null>
     */
    protected function logo(): Attribute
    {
        $logo = $this->getFirstMediaUrl(MediaCollectionName::LOGO->value);

        return Attribute::make(
            get: fn (): ?string => $logo === '' ? null : $logo,
        );
    }

    /**
     * Get the church logo.
     *
     * @return Attribute<string|null,null>
     */
    protected function logoPath(): Attribute
    {
        $logo = $this->getFirstMediaPath(MediaCollectionName::LOGO->value);

        return Attribute::make(
            get: fn (): ?string => $logo === '' ? null : $logo,
        );
    }

    #[\Override]
    protected function casts(): array
    {
        return [
            ...parent::casts(),
            'active' => 'boolean',
        ];
    }
}
