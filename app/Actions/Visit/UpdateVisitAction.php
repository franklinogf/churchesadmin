<?php

declare(strict_types=1);

namespace App\Actions\Visit;

use App\Enums\ModelMorphName;
use App\Models\Visit;
use App\Support\ArrayFallback;
use App\Support\DiffLogger;
use Illuminate\Support\Facades\DB;

final class UpdateVisitAction
{
    /**
     * Handle the action.
     *
     * @param  array{name?:string,last_name?:string,email?:string|null,phone?:string|null,first_visit_date?:string|null}  $data
     * @param  array{address_1:string,address_2:string|null,city:string,state:string,zip_code:string,country:string}|array{}|null  $address
     */
    public function handle(Visit $visit, array $data, ?array $address = []): Visit
    {
        return DB::transaction(function () use ($visit, $data, $address): Visit {
            $logger = new DiffLogger();
            $originalVisit = $visit->replicate();
            $originalAddress = $visit->address?->only(['address_1', 'address_2', 'city', 'state', 'zip_code', 'country']);

            $visit->update([
                'name' => $data['name'] ?? $visit->name,
                'last_name' => $data['last_name'] ?? $visit->last_name,
                'email' => ArrayFallback::inputOrFallback($data, 'email', $visit->email),
                'phone' => ArrayFallback::inputOrFallback($data, 'phone', $visit->phone),
                'first_visit_date' => ArrayFallback::inputOrFallback($data, 'first_visit_date', $visit->first_visit_date),
            ]);

            $freshVisit = $visit->fresh();
            if ($freshVisit !== null) {
                $logger->compareModels($originalVisit, $freshVisit, [
                    'name', 'last_name', 'email', 'phone', 'first_visit_date',
                ]);
            }

            if ($address !== [] && $address !== null) {
                if ($visit->address !== null) {
                    $visit->address()->update($address);
                    $visit->load('address'); // Reload to get fresh data
                    $newAddress = $visit->address?->only(array_keys($originalAddress ?? []));

                    $logger->addChanges(['address' => $originalAddress], ['address' => $newAddress]);
                } else {
                    $visit->address()->create($address);
                    $logger->addCustom('address', null, $address);
                }
            } elseif ($address === null) {
                $visit->address()->delete();
                $logger->addCustom('address', $originalAddress, null);
            }

            if ($logger->hasChanges()) {
                activity(ModelMorphName::VISIT->activityLogName())
                    ->event('updated')
                    ->performedOn($visit)
                    ->withProperties($logger->get())
                    ->log($logger->getSummary());
            }

            return $visit;
        });
    }
}
