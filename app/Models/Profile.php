<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;

    /**
     * Get the user that owns the profile.
     *
     * @return BelongsTo<User, Profile>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the addresses for the profile.
     *
     * @return HasMany<Address, Profile>
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the primary address for the profile.
     *
     * @return HasOne<Address, Profile>
     */
    public function primaryAddress(): HasOne
    {
        return $this->addresses()->one()->where('is_primary', true);
    }
}
