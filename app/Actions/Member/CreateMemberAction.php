<?php

declare(strict_types=1);

namespace App\Actions\Member;

use App\Enums\TagType;
use App\Models\Member;

final class CreateMemberAction
{
    public function handle(array $data, ?array $skills, ?array $categories, ?array $address): void
    {
        $member = Member::create($data);

        if ($skills !== null) {
            $member->attachTags($skills, TagType::SKILL->value);
        }
        if ($categories !== null) {
            $member->attachTags($categories, TagType::CATEGORY->value);
        }

        if ($address !== null) {
            $member->address()->create($address);
        }
    }
}
