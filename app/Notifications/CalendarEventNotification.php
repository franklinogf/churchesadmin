<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\CalendarEvent;
use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class CalendarEventNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public CalendarEvent $calendarEvent)
    {
        $this->onQueue('emails');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  Member  $notifiable
     */
    public function toMail(object $notifiable): MailMessage
    {
        $greetingName = "{$notifiable->name} {$notifiable->last_name}";

        return (new MailMessage)
            ->tag('calendar-event')
            ->greeting(__('notifications.calendar_event.greeting', ['name' => $greetingName]))
            ->subject(__('notifications.calendar_event.subject', ['title' => $this->calendarEvent->title]))
            ->line(__('notifications.calendar_event.line_1'))
            ->line(__('notifications.calendar_event.line_2', ['title' => $this->calendarEvent->title]))
            ->lineIf($this->calendarEvent->location !== null, __('notifications.calendar_event.line_3', ['location' => $this->calendarEvent->location]))
            ->line(__('notifications.calendar_event.line_4', ['start' => display_date($this->calendarEvent->start_at, 'Y-m-d H:i')]))
            ->line(__('notifications.calendar_event.line_5', ['end' => display_date($this->calendarEvent->end_at, 'Y-m-d H:i')]))
            ->line(__('notifications.calendar_event.line_6', ['description' => $this->calendarEvent->description]))
            ->line(__('notifications.calendar_event.line_7', ['timezone' => config('app.timezone_display')]));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->calendarEvent->title,
            'location' => $this->calendarEvent->location,
            'start_at' => $this->calendarEvent->start_at,
            'end_at' => $this->calendarEvent->end_at,
            'description' => $this->calendarEvent->description,
        ];
    }
}
