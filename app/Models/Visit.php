<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsUcWords;
use App\Models\Scopes\LastnameScope;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $last_name
 * @property-read string|null $phone
 * @property-read string|null $email
 * @property-read CarbonImmutable|null $first_visit_date
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 * @property-read CarbonImmutable|null $deleted_at
 * @property-read Address|null $address
 * @property-read Collection<int,FollowUp> $followUps
 * @property-read FollowUp|null $lastFollowUp
 * @property-read Collection<int,Email> $emails
 *  @property-read Emailable $emailMessage
 */
#[ScopedBy(LastnameScope::class)]
final class Visit extends Model
{
    /** @use HasFactory<\Database\Factories\VisitFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The address of the visit.
     *
     * @return MorphOne<Address, $this>
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'owner');
    }

    /**
     * The follow-ups associated with the visit.
     *
     * @return HasMany<FollowUp, $this>
     */
    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }

    /**
     * The last follow-up associated with the visit.
     *
     * @return HasOne<FollowUp, $this>
     */
    public function lastFollowUp(): HasOne
    {
        return $this->followUps()->one()->latestOfMany();
    }

    /**
     * The emails that has been sent to this visit.
     *
     * @return MorphToMany<Email, $this, Emailable, 'message'>
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
     * Scope a query to only include visits with an email.
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
    #[\Override]
    protected function casts(): array
    {
        return [
            'first_visit_date' => 'immutable_date',
            'name' => AsUcWords::class,
            'last_name' => AsUcWords::class,
        ];
    }
}
