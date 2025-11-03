<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsUcWords;
use App\Enums\CivilStatus;
use App\Enums\Gender;
use App\Enums\ModelMorphName;
use App\Models\Scopes\ActiveMemberScope;
use App\Models\Scopes\CurrentYearScope;
use App\Models\Scopes\LastnameScope;
use App\Models\Traits\HasTags;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection as SupportCollection;

/**
 * Member model.
 *
 * @property-read int $id
 * @property-read string $name
 * @property-read string $last_name
 * @property-read string|null $email
 * @property-read string|null $phone
 * @property-read Gender $gender
 * @property-read CarbonImmutable|null $dob
 * @property-read CarbonImmutable|null $baptism_date
 * @property-read CivilStatus $civil_status
 * @property-read bool $active
 * @property-read int|null $deactivation_code_id
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 * @property-read Address|null $address
 * @property-read DeactivationCode|null $deactivationCode
 * @property-read Collection<int,Tag> $tags
 * @property-read Collection<int,Email> $emails
 * @property-read Emailable $emailMessage
 * @property-read Collection<int,Offering> $contributions
 * @property-read Collection<int,Offering> $previousYearContributions
 */
#[ScopedBy([LastnameScope::class, ActiveMemberScope::class])]
final class Member extends Model
{
    use HasFactory;
    use HasTags;

    /**
     * The name of the table associated with the Tag model.
     */
    public static function getTagClassName(): string
    {
        return Tag::class;
    }

    /**
     * The address of the member.
     *
     * @return MorphOne<Address, $this>
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'owner');
    }

    /**
     * The deactivation code used for this member.
     *
     * @return BelongsTo<DeactivationCode, $this>
     */
    public function deactivationCode(): BelongsTo
    {
        return $this->belongsTo(DeactivationCode::class);
    }

    /**
     * The emails that has been sent to this member.
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
     * The contributions made by the member.
     *
     * @return HasMany<Offering, $this>
     */
    public function contributions(): HasMany
    {
        return $this->hasMany(Offering::class, 'donor_id');
    }

    /**
     * The contributions made by the member in the previous year.
     *
     * @return HasMany<Offering, $this>
     */
    public function previousYearContributions(): HasMany
    {
        return $this->contributions()
            ->withoutGlobalScope(CurrentYearScope::class);
    }

    public function getPreviousYearContributionsAmount(string|int|null|CurrentYear $year = null): int
    {
        if ($year !== null) {
            $yearId = $year instanceof CurrentYear ? $year->id : CurrentYear::query()->ofYear((string) $year)->firstOrFail()->id;

            return $this->previousYearContributions()
                ->where('current_year_id', $yearId)
                ->get()
                ->sum('transaction.amount');
        }

        return $this->previousYearContributions->sum('transaction.amount');
    }

    public function getPreviousYearContributions(string|int|null|CurrentYear $year = null): SupportCollection
    {
        $previousYearContributions = $this->previousYearContributions()->with('offeringType', 'transaction');
        if ($year !== null) {
            $yearId = $year instanceof CurrentYear ? $year->id : CurrentYear::query()->ofYear((string) $year)->firstOrFail()->id;
            $previousYearContributions->where('current_year_id', $yearId);
        }

        return $previousYearContributions->get()->groupBy(fn (Offering $contribution): string => match ($contribution->offering_type_type) {
            ModelMorphName::OFFERING_TYPE->value => $contribution->offeringType->name,
            ModelMorphName::MISSIONARY->value => "{$contribution->offeringType->name} {$contribution->offeringType->last_name}",
        })->map(fn (SupportCollection $group): string => format_to_currency($group->sum('transaction.amount')));
    }

    public function getContributionsForYear(string|int|CurrentYear $year): array
    {
        return [
            'name' => "$this->last_name $this->name",
            'contributions' => $this->getPreviousYearContributions($year),
            'contributionAmount' => format_to_currency($this->getPreviousYearContributionsAmount($year)),
        ];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {

        return [
            'active' => 'boolean',
            'gender' => Gender::class,
            'dob' => 'immutable_date',
            'baptism_date' => 'immutable_date',
            'civil_status' => CivilStatus::class,
            // 'name' => AsUcWords::class,
            // 'last_name' => AsUcWords::class,
        ];
    }
}
