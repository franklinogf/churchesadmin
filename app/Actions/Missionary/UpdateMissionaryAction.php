<?php

declare(strict_types=1);

namespace App\Actions\Missionary;

use App\Enums\Gender;
use App\Enums\ModelMorphName;
use App\Enums\OfferingFrequency;
use App\Models\Missionary;
use App\Support\ArrayFallback;
use App\Support\DiffLogger;

final class UpdateMissionaryAction
{
    /**
     * Handle the action.
     *
     * @param  array{name?:string,last_name?:string,email?:string|null,phone?:string|null,gender?:Gender,church?:string|null,offering?:string|null,offering_frequency?:OfferingFrequency|null}  $data
     * @param  array<string,mixed>|null  $address
     */
    public function handle(Missionary $missionary, array $data, ?array $address = null): void
    {
        $logger = new DiffLogger();
        $originalMissionary = $missionary->replicate();
        $originalAddress = $missionary->address?->only(['address_1', 'address_2', 'city', 'state', 'zip_code', 'country']);
        $missionary->update([
            'name' => ArrayFallback::inputOrFallback($data, 'name', $missionary->name),
            'last_name' => ArrayFallback::inputOrFallback($data, 'last_name', $missionary->last_name),
            'email' => ArrayFallback::inputOrFallback($data, 'email', $missionary->email),
            'phone' => ArrayFallback::inputOrFallback($data, 'phone', $missionary->phone),
            'gender' => ArrayFallback::inputOrFallback($data, 'gender', $missionary->gender),
            'church' => ArrayFallback::inputOrFallback($data, 'church', $missionary->church),
            'offering' => ArrayFallback::inputOrFallback($data, 'offering', $missionary->offering),
            'offering_frequency' => ArrayFallback::inputOrFallback($data, 'offering_frequency', $missionary->offering_frequency),
        ]);
        $freshMissionary = $missionary->fresh();
        if ($freshMissionary !== null) {
            $logger->compareModels($originalMissionary, $freshMissionary, [
                'name', 'last_name', 'email', 'phone', 'gender', 'church', 'offering', 'offering_frequency',
            ]);
        }

        $this->handleAddressUpdates($missionary, $address, $originalAddress, $logger);

        // Log activity if there are changes
        if ($logger->hasChanges()) {
            activity(ModelMorphName::MISSIONARY->activityLogName())
                ->event('updated')
                ->performedOn($missionary)
                ->withProperties($logger->get())
                ->log($logger->getSummary());
        }
    }

    /**
     * Handle address updates and logging.
     *
     * @param  array<string, mixed>|null  $address
     * @param  array<string, mixed>|null  $originalAddress
     */
    private function handleAddressUpdates(Missionary $missionary, ?array $address, ?array $originalAddress, DiffLogger $logger): void
    {
        if ($address !== [] && $address !== null) {
            if ($missionary->address !== null) {
                $missionary->address()->update($address);
                $missionary->load('address'); // Reload to get fresh data
                $newAddress = $missionary->address?->only(array_keys($originalAddress ?? []));

                $logger->addChanges(['address' => $originalAddress], ['address' => $newAddress]);

            } else {
                $missionary->address()->create($address);
                $logger->addCustom('address', null, $address);
            }
        } elseif ($address === null && $missionary->address !== null) {
            $missionary->address()->delete();
            $logger->addCustom('address', $originalAddress, null);
        }
    }
}
