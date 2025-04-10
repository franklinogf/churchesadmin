<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use App\Models\Tag;

final class UpdateTagAction
{
    public function handle(Tag $tag, array $data): void
    {
        $tag->update([
            'name' => $data['name'],
            'is_regular' => $data['is_regular'],
        ]);
    }
}
