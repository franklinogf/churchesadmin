<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsUcWords;
use App\Enums\CivilStatus;
use App\Enums\Gender;
use App\Models\Scopes\LastnameScope;
use App\Models\Traits\HasTags;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Member model.
 *
 * @property-read int $id
 * @property-read string $name
 * @property-read string $last_name
 * @property-read string $email
 * @property-read string $phone
 * @property-read Gender $gender
 * @property-read CarbonImmutable|null $dob
 * @property-read CivilStatus $civil_status
 * @property-read CarbonImmutable|null $deleted_at
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 * @property-read Address|null $address
 * @property-read Collection<int,Tag> $tags
 * @property-read Collection<int,Email> $emails
 * @property-read Emailable $emailMessage
 */
#[ScopedBy(LastnameScope::class)]
final class Member extends Model
{
    /** @use HasFactory<\Database\Factories\MemberFactory> */
    use HasFactory, HasTags, SoftDeletes;

    protected $connection = 'tenant';

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {

        return [
            'gender' => Gender::class,
            'dob' => 'immutable_date',
            'civil_status' => CivilStatus::class,
            'name' => AsUcWords::class,
            'last_name' => AsUcWords::class,
        ];
    }
}
