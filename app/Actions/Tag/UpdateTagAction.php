<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use App\Enums\LanguageCode;
use App\Models\Tag;

final class UpdateTagAction
{
    /**
     * Handle the action.
     *
     * @param  array{name?:string, is_regular?:bool}  $data
     */
    public function handle(Tag $tag, array $data): Tag
    {
        $tag->update([
            'name' => isset($data['name']) ? collect(LanguageCode::values())
                ->mapWithKeys(fn (string $code) => [$code => $data['name']])
                ->toArray() : $tag->name,
            'is_regular' => $data['is_regular'] ?? $tag->is_regular,
        ]);

        return $tag;
    }
}
