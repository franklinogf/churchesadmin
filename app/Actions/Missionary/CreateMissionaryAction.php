<?php

declare(strict_types=1);

namespace App\Actions\Missionary;

use App\Enums\ModelMorphName;
use App\Models\Missionary;
use App\Support\DiffLogger;
use Illuminate\Support\Facades\DB;

final class CreateMissionaryAction
{
    /**
     * Handle the action.
     *
     * @param  array<string,mixed>  $data
     * @param  array<string,mixed>|null  $address
     */
    public function handle(array $data, ?array $address = null): Missionary
    {
        return DB::transaction(function () use ($data, $address): Missionary {
            $logger = new DiffLogger;
            $missionary = Missionary::create($data);

            // Log missionary creation with all provided data
            $missionaryData = $missionary->only([
                'name', 'last_name', 'email', 'phone', 'gender', 'dob', 'civil_status',
            ]);
            $logger->addCustom('missionary', null, $missionaryData);

            if ($address !== null) {
                $missionary->address()->create($address);
                $logger->addCustom('address', null, $address);
            }

            activity(ModelMorphName::MISSIONARY->activityLogName())
                ->event('created')
                ->performedOn($missionary)
                ->withProperties($logger->get())
                ->log($logger->getSummary());

            return $missionary;
        });
    }
}
