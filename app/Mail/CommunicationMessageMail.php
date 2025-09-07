<?php

declare(strict_types=1);

namespace App\Mail;

use App\Enums\MediaCollectionName;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class CommunicationMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Email $email,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: $this->email->reply_to ? [
                new Address($this->email->reply_to),
            ] : [],
            subject: $this->email->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.communication-message',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        /**
         * @var array<int, Attachment> $attachments
         */
        $attachments = $this->email->getMedia(MediaCollectionName::ATTACHMENT->value)
            ->map(
                fn (Media $media): Attachment => Attachment::fromPath($media->getPath())
                    ->as($media->file_name)
                    ->withMime($media->mime_type)

            )
            ->toArray();

        return $attachments;
    }
}
