<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsUcWords;
use App\Enums\CivilStatus;
use App\Enums\Gender;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

/**
 * Member model.
 *
 * @property-read int $id
 * @property-read string $name
 * @property-read string $last_name
 * @property-read string $email
 * @property-read string $phone
 * @property-read Gender $gender
 * @property-read DateTimeInterface|null $dob
 * @property-read CivilStatus $civil_status
 * @property-read DateTimeInterface|null $deleted_at
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 * @property-read Address|null $address
 * @property-read Collection<int,Tag> $tags
 */
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
     * The address that has the member.
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
     * @return MorphToMany<Email, $this>
     */
    public function emails(): MorphToMany
    {
        return $this->morphToMany(Email::class, 'recipient', 'emailables')
            ->as('message')
            ->withPivot('status', 'sent_at', 'error_message')
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
