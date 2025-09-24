<?php

declare(strict_types=1);

namespace App\Actions\Missionary;

use App\Enums\Gender;
use App\Enums\OfferingFrequency;
use App\Models\Missionary;
use App\Support\ArrayFallback;

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

        if ($address !== null) {
            if ($missionary->address !== null) {
                $missionary->address()->update($address);
            } else {
                $missionary->address()->create($address);
            }
        } else {
            $missionary->address()->delete();
        }
    }
}
