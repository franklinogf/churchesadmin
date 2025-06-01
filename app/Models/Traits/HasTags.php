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
            if (method_exists($deletedModel, 'isForceDeleting') && ! $deletedModel->isForceDeleting()) {
                return;
            }

            $tags = $deletedModel->tags()->get();
            $deletedModel->detachTags($tags);
        });
    }
}
