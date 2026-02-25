<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\CurrentYear;
use App\Models\Member;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use function is_string;

final class ContributionReportMail extends Mailable
{
    use Queueable, SerializesModels;

    private string $selectedYear;

    /**
     * Create a new message instance.
     */
    public function __construct(public Member $member, public CurrentYear|string $year)
    {
        $this->selectedYear = is_string($this->year) ? $this->year : $this->year->year;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contribution Report',

        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        return new Content(
            markdown: 'mail.contribution_report',
            with: [
                'contributionAmount' => format_to_currency($this->member->getPreviousYearContributionsAmount($this->selectedYear)),
                'year' => $this->selectedYear,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.contribution', [
            'title' => __('Contributions Report for year :year', ['year' => $this->selectedYear]),
            'contribution' => $this->member->getContributionsForYear($this->selectedYear),
            'year' => $this->selectedYear,
        ])
            ->setPaper('letter', 'portrait')
            ->output();

        return [
            Attachment::fromData(fn (): string => $pdf, "contribution_report_{$this->selectedYear}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
