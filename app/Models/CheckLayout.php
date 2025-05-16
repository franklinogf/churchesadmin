<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MediaCollectionName;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read array $fields
 * @property-read int $width
 * @property-read int $height
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 * @property-read DateTimeInterface|null $deleted_at
 */
final class CheckLayout extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * Checks that use this layout.
     *
     * @return HasMany<ChurchWallet, $this>
     */
    public function wallets(): HasMany
    {
        return $this->hasMany(ChurchWallet::class);
    }

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
            ->acceptsFile(function (File $file) {
                return str($file->mimeType)->startsWith('image/');
            });
    }
}
