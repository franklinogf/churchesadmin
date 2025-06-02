<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CheckLayoutField;
use App\Enums\MediaCollectionName;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read array<CheckLayoutField,array{position:array{x:float,y:float}}> $fields
 * @property-read int $width
 * @property-read int $height
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 * @property-read DateTimeInterface|null $deleted_at
 * @property-read string $imageUrl
 */
final class CheckLayout extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\CheckLayoutFactory> */
    use HasFactory,InteractsWithMedia;

    /**
     * Checks that use this layout.
     *
     * @return HasMany<ChurchWallet, $this>
     */
    public function wallets(): HasMany
    {
        return $this->hasMany(ChurchWallet::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string,string>
     */
    public function casts(): array
    {
        return [
            'fields' => 'json',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionName::DEFAULT->value)
            ->singleFile()
            ->acceptsFile(fn (File $file) => str($file->mimeType)->startsWith('image/'));
    }

    /**
     * Get the URL of the layout image.
     *
     * @return Attribute<string,null>
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->getFirstMediaUrl(MediaCollectionName::DEFAULT->value),
        );
    }
}
