<?php

declare(strict_types=1);

namespace App\Actions\Member;

use App\Enums\TagType;
use App\Models\Member;

final class UpdateMemberAction
{
    /**
     * Handle the action.
     *
     * @param  array<string,mixed>  $data
     * @param  array<int,string>|null  $skills
     * @param  array<int,string>|null  $categories
     * @param  array<string,mixed>|null  $address
     */
    public function handle(Member $member, array $data, ?array $skills, ?array $categories, ?array $address): void
    {

        $member->update($data);

        if ($skills !== null) {
            $member->syncTagsWithType($skills, TagType::SKILL->value);
        }

        if ($categories !== null) {
            $member->syncTagsWithType($categories, TagType::CATEGORY->value);
        }

        if ($address !== null) {
            $member->address()->update($address);
        } else {
            $member->address()->delete();
        }
    }
}
