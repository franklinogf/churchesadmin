<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Tags\HasTags;

/**
 * Member model.
 *
 * @property-read int $id
 * @property-read string $name
 * @property-read string $last_name
 * @property-read string $email
 * @property-read string|null $phone
 * @property-read \App\Enums\Gender $gender
 * @property-read \Carbon\CarbonImmutable $dob
 * @property-read \App\Enums\CivilStatus $civil_status
 * @property-read \Carbon\CarbonImmutable|null $deleted_at
 * @property-read \Carbon\CarbonImmutable|null $created_at
 * @property-read \Carbon\CarbonImmutable|null $updated_at
 * @property-read Address $address
 * @property-read \Illuminate\Database\Eloquent\Collection|Tag[] $tags
 */
final class Member extends Model
{
    /** @use HasFactory<\Database\Factories\MemberFactory> */
    use HasFactory, HasTags;

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
            'gender' => \App\Enums\Gender::class,
            'dob' => 'date',
            'civil_status' => \App\Enums\CivilStatus::class,
        ];
    }
}
