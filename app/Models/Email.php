<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmailStatus;
use App\Enums\ModelMorphName;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property-read int $id
 * @property-read string $subject
 * @property-read string $body
 * @property-read string $sender_id
 * @property-read ModelMorphName $recipients_type
 * @property-read string|null $reply_to
 * @property-read EmailStatus $status
 * @property-read CarbonImmutable|null $sent_at
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 * @property-read TenantUser $sender
 * @property-read Collection<int,Member> $members
 * @property-read Collection<int,Missionary> $missionaries
 * @property-read Emailable $message
 */
final class Email extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\EmailFactory> */
    use HasFactory, InteractsWithMedia;

    /**
     * The email's sender
     *
     * @return BelongsTo<TenantUser, $this>
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(TenantUser::class, 'sender_id');
    }

    /**
     * The members that this email was sent to.
     *
     * @return MorphToMany<Member,$this,Emailable,'emailMessage'>
     */
    public function members(): MorphToMany
    {
        return $this->morphedByMany(Member::class, 'recipient', 'emailables')
            ->using(Emailable::class)
            ->as('emailMessage')
            ->withPivot('status', 'sent_at', 'error_message', 'id')
            ->withTimestamps();
    }

    /**
     * The missionaries that this email was sent to.
     *
     * @return MorphToMany<Missionary,$this,Emailable,'emailMessage'>
     */
    public function missionaries(): MorphToMany
    {
        return $this->morphedByMany(Missionary::class, 'recipient', 'emailables')
            ->using(Emailable::class)
            ->as('emailMessage')
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
            'status' => EmailStatus::class,
            'recipients_type' => ModelMorphName::class,
            'sent_at' => 'datetime',
        ];
    }
}
