<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsUcWords;
use App\Enums\Gender;
use App\Enums\OfferingFrequency;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Missionary model.
 *
 * @property-read int $id
 * @property-read string $name
 * @property-read string $last_name
 * @property-read string|null $email
 * @property-read string|null $phone
 * @property-read Gender $gender
 * @property-read string|null $church
 * @property-read float|null $offering
 * @property-read OfferingFrequency|null $offering_frequency
 * @property-read DateTimeInterface|null $deleted_at
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 * @property-read Address|null $address
 * @property-read Offering[] $offerings
 * @property-read Email[] $emails
 * @property-read Emailable $emailMessage
 */
final class Missionary extends Model
{
    /** @use HasFactory<\Database\Factories\MissionaryFactory> */
    use HasFactory,SoftDeletes;

    /**
     * Get the address of this model.
     *
     * @return MorphOne<Address, $this>
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'owner');
    }

    /**
     * Get the offerings of this model.
     *
     * @return MorphMany<Offering, $this>
     */
    public function offerings(): MorphMany
    {
        return $this->morphMany(Offering::class, 'offering_type');
    }

    /**
     * The emails that has been sent to this missionary.
     *
     * @return MorphToMany<Email, $this, MorphPivot, 'message'>
     */
    public function emails(): MorphToMany
    {
        return $this->morphToMany(Email::class, 'recipient', 'emailables')
            ->using(Emailable::class)
            ->as('message')
            ->withPivot('status', 'sent_at', 'error_message', 'id')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include missionaries with an email.
     *
     * @param  Builder<$this>  $query
     */
    protected function scopeWithEmail(Builder $query): void
    {
        $query->whereNotNull('email');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
            'offering_frequency' => OfferingFrequency::class,
            'offering' => 'decimal:2',
            'name' => AsUcWords::class,
            'last_name' => AsUcWords::class,
        ];
    }
}
