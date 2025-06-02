<?php

declare(strict_types=1);

use App\Actions\Tag\DeleteTagAction;
use App\Models\Tag;

it('can delete a tag', function (): void {
    $tag = Tag::factory()->create();

    $action = new DeleteTagAction();
    $action->handle($tag);

    expect(Tag::find($tag->id))->toBeNull();
});
