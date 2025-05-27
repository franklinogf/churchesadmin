<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmailStatus;
use App\Enums\ModelMorphName;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 *  @property-read int $id
 * @property-read string $subject
 * @property-read string $body
 * @property-read string $sender_id
 * @property-read ModelMorphName $recipients_type
 * @property-read string|null $reply_to
 * @property-read EmailStatus $status
 * @property-read DateTimeInterface|null $sent_at
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 * @property-read TenantUser $sender
 * @property-read Member[] $members
 * @property-read Member[] $pendingMembers
 * @property-read Missionary[] $missionaries
 * @property-read Missionary[] $pendingMissionaries
 * @property-read Emailable $message
 */
final class Email extends Model implements HasMedia
{
    use InteractsWithMedia;

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
     * The members that this email is pending to send.
     *
     * @return MorphToMany<Member,$this,Emailable,'emailMessage'>
     */
    public function pendingMembers(): MorphToMany
    {
        return $this->morphedByMany(Member::class, 'recipient', 'emailables')
            ->using(Emailable::class)
            ->as('emailMessage')
            ->withPivot('status', 'sent_at', 'error_message', 'id')
            ->withTimestamps()
            ->wherePivot('status', EmailStatus::PENDING);
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
     * The missionaries that this email is pending to send.
     *
     * @return MorphToMany<Missionary,$this,Emailable,'emailMessage'>
     */
    public function pendingMissionaries(): MorphToMany
    {
        return $this->morphedByMany(Missionary::class, 'recipient', 'emailables')
            ->using(Emailable::class)
            ->as('emailMessage')
            ->withPivot('status', 'sent_at', 'error_message', 'id')
            ->withTimestamps()
            ->wherePivot('status', EmailStatus::PENDING);
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
