<?php

declare(strict_types=1);

namespace App\Actions\Member;

use App\Enums\CivilStatus;
use App\Enums\Gender;
use App\Enums\ModelMorphName;
use App\Enums\TagType;
use App\Models\Member;
use App\Support\DiffLogger;
use Illuminate\Support\Facades\DB;

final class CreateMemberAction
{
    /**
     * Handle the action.
     *
     * @param  array{
     * name:string,
     * last_name:string,
     * email:string|null,
     * phone:string|null,
     * gender:Gender,
     * dob?:string|null,
     * baptism_date?:string|null,
     * civil_status:CivilStatus,
     * skills?:array<int,string>|null|array{},
     * categories?:array<int,string>|null|array{}
     * }  $data
     * @param  array{address_1:string,address_2:string|null,city:string,state:string,zip_code:string,country:string}|null  $address
     */
    public function handle(array $data, ?array $address = null): Member
    {
        return DB::transaction(function () use ($data, $address): Member {
            // Create a logger for tracking the creation
            $logger = new DiffLogger();

            $member = Member::create([
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'gender' => $data['gender'],
                'dob' => $data['dob'] ?? null,
                'baptism_date' => $data['baptism_date'] ?? null,
                'civil_status' => $data['civil_status'],
            ]);

            // Log member creation with all provided data
            $memberData = $member->only([
                'name', 'last_name', 'email', 'phone', 'gender', 'dob', 'baptism_date', 'civil_status',
            ]);
            $logger->addChanges([], $memberData);

            // Handle skills
            if (isset($data['skills']) && $data['skills'] !== []) {
                $member->attachTags($data['skills'], TagType::SKILL->value);
                $logger->addCustom('skills', null, $data['skills']);
            }

            // Handle categories
            if (isset($data['categories']) && $data['categories'] !== []) {
                $member->attachTags($data['categories'], TagType::CATEGORY->value);
                $logger->addCustom('categories', null, $data['categories']);
            }

            // Handle address
            if ($address !== null) {
                $member->address()->create($address);
                $logger->addCustom('address', null, $address);
            }

            activity(ModelMorphName::MEMBER->activityLogName())
                ->event('created')
                ->performedOn($member)
                ->withProperties($logger->get())
                ->log('Member added');

            return $member;
        });
    }
}
