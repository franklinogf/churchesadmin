<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmailStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class Email extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * The members that this email was sent to.
     *
     * @return MorphToMany<Member, $this>
     */
    public function members(): MorphToMany
    {
        return $this->morphedByMany(Member::class, 'recipient', 'emailables');
    }

    /**
     * The missionaries that this email was sent to.
     *
     * @return MorphToMany<Missionary, $this>
     */
    public function missionaries(): MorphToMany
    {
        return $this->morphedByMany(Missionary::class, 'recipient', 'emailables');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => EmailStatus::class,
        ];
    }
}
