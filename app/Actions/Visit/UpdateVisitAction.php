<?php

declare(strict_types=1);

namespace App\Actions\Visit;

use App\Models\Visit;
use App\Support\ArrayFallback;

final class UpdateVisitAction
{
    /**
     * Handle the action.
     *
     * @param  array{name?:string,last_name?:string,email?:string|null,phone?:string,first_visit_date?:string|null}  $data
     * @param  array{address_1:string,address_2:string|null,city:string,state:string,zip_code:string,country:string}|array{}  $address
     */
    public function handle(Visit $visit, array $data, ?array $address = []): Visit
    {
        $visit->update([
            'name' => $data['name'] ?? $visit->name,
            'last_name' => $data['last_name'] ?? $visit->last_name,
            'email' => ArrayFallback::inputOrFallback($data, 'email', $visit->email),
            'phone' => $data['phone'] ?? $visit->phone,
            'first_visit_date' => ArrayFallback::inputOrFallback($data, 'first_visit_date', $visit->first_visit_date),
        ]);

        if ($address !== [] && $address !== null) {
            if ($visit->address !== null) {
                $visit->address()->update($address);
            } else {
                $visit->address()->create($address);
            }
        } elseif ($address === null) {
            $visit->address()->delete();
        }

        return $visit;
    }
}
