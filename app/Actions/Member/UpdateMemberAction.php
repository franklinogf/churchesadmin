<?php

declare(strict_types=1);

namespace App\Actions\Member;

use App\Enums\CivilStatus;
use App\Enums\Gender;
use App\Enums\TagType;
use App\Models\Member;
use App\Support\ArrayFallback;

final class UpdateMemberAction
{
    /**
     * Handle the action.
     *
     *@param  array{
     * name?:string,
     * last_name?:string,
     * email?:string,
     * phone?:string,
     * gender?:Gender,
     * dob?:string|null,
     * civil_status?:CivilStatus,
     * skills?:array<int,string>|null|array{},
     * categories?:array<int,string>|null|array{}
     * }  $data
     * @param  array{address_1:string,address_2:string|null,city:string,state:string,zip_code:string,country:string}|array{}|null  $address
     */
    public function handle(Member $member, array $data, ?array $address = []): void
    {

        $member->update([
            'name' => $data['name'] ?? $member->name,
            'last_name' => $data['last_name'] ?? $member->last_name,
            'email' => $data['email'] ?? $member->email,
            'phone' => $data['phone'] ?? $member->phone,
            'gender' => $data['gender'] ?? $member->gender,
            'dob' => ArrayFallback::inputOrFallback($data, 'dob', $member->dob),
            'civil_status' => $data['civil_status'] ?? $member->civil_status,
        ]);

        if (isset($data['skills'])) {
            $member->syncTagsWithType($data['skills'], TagType::SKILL->value);
        }

        if (isset($data['categories'])) {
            $member->syncTagsWithType($data['categories'], TagType::CATEGORY->value);
        }

        if ($address !== [] && $address !== null) {
            if ($member->address !== null) {
                $member->address()->update($address);
            } else {
                $member->address()->create($address);
            }
        } elseif ($address === null) {
            $member->address()->delete();
        }
    }
}
