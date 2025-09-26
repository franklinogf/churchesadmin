<?php

declare(strict_types=1);

namespace App\Actions\Member;

use App\Enums\CivilStatus;
use App\Enums\Gender;
use App\Enums\TagType;
use App\Models\Member;
use App\Support\ArrayFallback;
use App\Support\DiffLogger;

final class UpdateMemberAction
{
    public function __construct(private DiffLogger $logger) {}

    /**
     * Handle the action.
     *
     * @param  array{
     * name?:string,
     * last_name?:string,
     * email?:string|null,
     * phone?:string|null,
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

        // Capture original state
        $originalMember = $member->replicate();
        $originalTags = [
            'skills' => $member->tags()->where('type', TagType::SKILL->value)->pluck('name')->toArray(),
            'categories' => $member->tags()->where('type', TagType::CATEGORY->value)->pluck('name')->toArray(),
        ];
        $originalAddress = $member->address?->only(['address_1', 'address_2', 'city', 'state', 'zip_code', 'country']);

        // Update member
        $member->update([
            'name' => $data['name'] ?? $member->name,
            'last_name' => $data['last_name'] ?? $member->last_name,
            'email' => ArrayFallback::inputOrFallback($data, 'email', $member->email),
            'phone' => ArrayFallback::inputOrFallback($data, 'phone', $member->phone),
            'gender' => $data['gender'] ?? $member->gender,
            'dob' => ArrayFallback::inputOrFallback($data, 'dob', $member->dob),
            'civil_status' => $data['civil_status'] ?? $member->civil_status,
        ]);

        // Compare member changes
        $this->logger->compareModels($originalMember, $member->fresh(), [
            'name', 'last_name', 'email', 'phone', 'gender', 'dob', 'civil_status',
        ]);

        // Handle tags
        $this->handleTagsUpdates($member, $data, $originalTags);

        // Handle address
        $this->handleAddressUpdates($member, $address, $originalAddress);

        $this->logger->log($member, 'updated');
    }

    /**
     * Handle tags updates and logging.
     */
    private function handleTagsUpdates(Member $member, array $data, array $originalTags): void
    {
        foreach (['skills' => TagType::SKILL, 'categories' => TagType::CATEGORY] as $key => $type) {
            if (array_key_exists($key, $data)) {
                $member->syncTagsWithType($data[$key] ?? [], $type->value);
                $newTags = $member->tags()->where('type', $type->value)->pluck('name')->toArray();
                $this->logger->addCustom($key, $originalTags[$key], $newTags);
            }
        }
    }

    /**
     * Handle address updates and logging.
     */
    private function handleAddressUpdates(Member $member, ?array $address, ?array $originalAddress): void
    {
        if ($address !== [] && $address !== null) {
            if ($member->address !== null) {
                $member->address()->update($address);
                $member->load('address'); // Reload to get fresh data
                $newAddress = $member->address->only(array_keys($originalAddress ?? []));

                $this->logger->addChanges(['address' => $originalAddress], ['address' => $newAddress]);

            } else {
                $member->address()->create($address);
                $this->logger->addCustom('address', null, $address);
            }
        } elseif ($address === null && $member->address !== null) {
            $member->address()->delete();
            $this->logger->addCustom('address', $originalAddress, null);
        }
    }
}
