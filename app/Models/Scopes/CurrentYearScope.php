<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Models\TenantUser;
use App\Models\User;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

final class CurrentYearScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        /** @var TenantUser|User|null $user */
        $user = Auth::user();

        if ($user === null || ! $user instanceof TenantUser) {
            // If the user is not authenticated or not a TenantUser, skip the scope.
            return;
        }

        if ($model instanceof Transaction) {
            // If the model has a 'date' attribute, apply the scope to that attribute.
            $builder->where('meta->year', $user->current_year_id);

            return;
        }

        $builder->where('current_year_id', $user->current_year_id);
    }
}
