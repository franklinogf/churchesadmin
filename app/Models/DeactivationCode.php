<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\ActiveMemberScope;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 * @property-read Collection<int,Member> $members
 */
final class DeactivationCode extends Model
{
    /** @use HasFactory<\Database\Factories\DeactivationCodeFactory> */
    use HasFactory;

    /**
     * Members that have been deactivated with this code.
     *
     * @return HasMany<Member, $this>
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class)->withoutGlobalScope(ActiveMemberScope::class);
    }
}
