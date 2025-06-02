<?php

declare(strict_types=1);

namespace App\Actions\Member;

use App\Enums\TagType;
use App\Models\Member;

final class CreateMemberAction
{
    /**
     * Handle the action.
     *
     * @param  array<string,mixed>  $data
     * @param  array<int,string>|null  $skills
     * @param  array<int,string>|null  $categories
     * @param  array<string,mixed>|null  $address
     */
    public function handle(array $data, ?array $skills = null, ?array $categories = null, ?array $address = null): Member
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

        return $member;
    }
}
