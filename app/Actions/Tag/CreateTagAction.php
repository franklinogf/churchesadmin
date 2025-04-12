<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use App\Enums\TagType;
use App\Models\Tag;

final class CreateTagAction
{
    /**
     * Handle the action.
     *
     * @param  array<string,mixed>  $data
     */
    public function handle(array $data, ?TagType $type): void
    {

        Tag::create([
            'name' => $data['name'],
            'type' => $type?->value,
            'is_regular' => $data['is_regular'],
        ]);
    }
}
