<?php

namespace App\Notifications;

use App\Enums\ShiftStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AmbulanceShiftStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $status,
        public string $date
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = $this->status === ShiftStatus::Accepted->value
            ? __('app.notifications.shift_accepted_mail', ['date' => $this->date])
            : __('app.notifications.shift_rejected_mail', ['date' => $this->date]);

        return (new MailMessage)
            ->subject(__('app.notifications.shift_status_subject'))
            ->line($message);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => __('app.notifications.shift_status_title'),
            'message' => $this->status === ShiftStatus::Accepted->value
                ? __('app.notifications.shift_accepted_message', ['date' => $this->date])
                : __('app.notifications.shift_rejected_message', ['date' => $this->date]),
        ];
    }
}
