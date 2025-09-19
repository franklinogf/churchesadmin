<?php

declare(strict_types=1);

namespace App\Actions\Visit;

use App\Models\Visit;

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
        $visit = Visit::create($data);

        if ($address !== null) {
            $visit->address()->create($address);
        }

        return $visit;
    }
}
