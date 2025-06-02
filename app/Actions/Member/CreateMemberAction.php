<?php

declare(strict_types=1);

namespace App\Actions\Member;

use App\Enums\CivilStatus;
use App\Enums\Gender;
use App\Enums\TagType;
use App\Models\Member;
use Illuminate\Support\Facades\DB;

final class CreateMemberAction
{
    /**
     * Handle the action.
     *
     * @param  array{
     * name:string,
     * last_name:string,
     * email:string,
     * phone:string,
     * gender:Gender,
     * dob:string,
     * civil_status:CivilStatus,
     * skills?:array<int,string>|null|array{},
     * categories?:array<int,string>|null|array{}
     * }  $data
     * @param  array{address_1:string,address_2:string|null,city:string,state:string,zip_code:string,country:string}|null  $address
     */
    public function handle(array $data, ?array $address = null): Member
    {
        return DB::transaction(function () use ($data, $address) {
            $member = Member::create([
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'gender' => $data['gender'],
                'dob' => $data['dob'] ?? null,
                'civil_status' => $data['civil_status'],
            ]);

            if (isset($data['skills']) && $data['skills'] !== []) {
                $member->attachTags($data['skills'], TagType::SKILL->value);
            }
            if (isset($data['categories']) && $data['categories'] !== []) {
                $member->attachTags($data['categories'], TagType::CATEGORY->value);
            }

            if ($address !== null) {
                $member->address()->create($address);
            }

            return $member;
        });
    }
}
