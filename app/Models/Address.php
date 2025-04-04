<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

final class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    /**
     * Get the profile that owns the address.
     *
     * @return BelongsTo<Profile, Address>
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    /**
     * Get the user that owns the address.
     *
     * @return HasOneThrough<User, Profile>
     */
    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Profile::class,
            'id',
            'profile_id',
        );
    }
}
