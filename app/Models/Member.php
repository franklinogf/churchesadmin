<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CivilStatus;
use App\Enums\Gender;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
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
 * @property-read CarbonImmutable $dob
 * @property-read CivilStatus $civil_status
 * @property-read CarbonImmutable|null $deleted_at
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 * @property-read Address|null $address
 * @property-read Collection<int,Tag> $tags
 */
final class Member extends Model
{
    /** @use HasFactory<\Database\Factories\MemberFactory> */
    use HasFactory,HasTags, SoftDeletes;

    /**
     * The name of the table associated with the Tag model.
     *
     * @return string
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts()
    {

        return [
            'gender' => Gender::class,
            'dob' => 'date',
            'civil_status' => CivilStatus::class,
        ];
    }
}
