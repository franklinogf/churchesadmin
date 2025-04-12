<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use App\Models\Tag;

final class DeleteTagAction
{
    /**
     * Handle the action.
     */
    public function handle(Tag $tag): void
    {
        $tag->delete();
    }
}
