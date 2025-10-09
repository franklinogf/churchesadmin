<?php

declare(strict_types=1);

namespace App\Actions\Visit;

use App\Enums\ModelMorphName;
use App\Models\Visit;
use App\Support\DiffLogger;
use Illuminate\Support\Facades\DB;

final class CreateVisitAction
{
    /**
     * Handle the action.
     *
     * @param  array{name:string,last_name:string,email:string|null,phone:string|null,first_visit_date:string|null}  $data
     * @param  array{address_1:string,address_2:string|null,city:string,state:string,zip_code:string,country:string}  $address
     */
    public function handle(array $data, ?array $address = null): Visit
    {
        return DB::transaction(function () use ($data, $address): Visit {
            $logger = new DiffLogger();
            $visit = Visit::create($data);
            $visitData = $visit->only([
                'name', 'last_name', 'email', 'phone', 'first_visit_date',
            ]);
            $logger->addChanges([], $visitData);

            if ($address !== null) {
                $visit->address()->create($address);
                $logger->addCustom('address', null, $address);
            }

            activity(ModelMorphName::VISIT->activityLogName())
                ->event('created')
                ->performedOn($visit)
                ->withProperties($logger->get())
                ->log('Visit added');

            return $visit;
        });
    }
}
