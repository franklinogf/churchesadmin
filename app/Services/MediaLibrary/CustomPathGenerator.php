<?php

declare(strict_types=1);

namespace App\Services\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

final class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media).'/';
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media).'/conversions/';
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media).'/responsive-images/';
    }

    /*
     * Get the path for the given media, relative to the root storage path.
     */
    private function getBasePath(Media $media): string
    {
        $prefix = config('media-library.prefix', '');

        $id = $media->uuid;
        $url = "{$media->model_type}/{$media->collection_name}/{$id}";

        if ($prefix !== '') {
            return "{$prefix}/{$url}";
        }

        return $url;
    }
}
