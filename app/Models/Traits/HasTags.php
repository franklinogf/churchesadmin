<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags as Spatie;

trait HasTags
{
    use Spatie;

    public static function bootHasTags(): void
    {
        static::deleted(function (Model $deletedModel): void {
            // @phpstan-ignore-next-line
            if (method_exists($deletedModel, 'isForceDeleting') && ! $deletedModel->isForceDeleting()) {
                return;
            }

            // @phpstan-ignore-next-line
            $tags = $deletedModel->tags()->get();
            // @phpstan-ignore-next-line
            $deletedModel->detachTags($tags);
        });
    }
}
