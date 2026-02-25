<?php

declare(strict_types=1);

namespace App\Http\Resources\Spatie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin Media
 */
final class MediaResource extends JsonResource
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
            'uuid' => $this->uuid,
            'name' => $this->name,
            'fileName' => $this->file_name,
            'fileSize' => $this->size,
            'fileSizeFormatted' => $this->human_readable_size,
            'fileType' => $this->mime_type,
            'modelType' => $this->model_type,
            'modelId' => $this->model_id,
            'order' => $this->order_column,
            'url' => $this->getFullUrl(),
            'collectionName' => $this->collection_name,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
