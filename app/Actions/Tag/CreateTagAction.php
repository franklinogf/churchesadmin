<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use App\Enums\LanguageCode;
use App\Enums\TagType;
use App\Models\Tag;

final class CreateTagAction
{
    /**
     * Handle the action.
     *
     * @param  array{name:string, is_regular?:bool}  $data
     */
    public function handle(array $data, ?TagType $type = null): Tag
    {
        return Tag::create([
            'name' => collect(LanguageCode::values())
                ->mapWithKeys(fn (string $code) => [$code => $data['name']])
                ->toArray(),
            'type' => $type?->value,
            'is_regular' => $data['is_regular'] ?? false,
        ]);
    }
}
