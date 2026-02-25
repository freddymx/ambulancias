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
            ? "¡Tu turno de ambulancia ha sido confirmado para el {$this->date}!"
            : "Lo sentimos, tu solicitud de turno para el {$this->date} ha sido rechazada.";

        return (new MailMessage)
            ->subject('Estado de tu turno de ambulancia')
            ->line($message);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Estado de turno actualizado',
            'message' => $this->status === ShiftStatus::Accepted->value
                ? "Tu turno para el {$this->date} ha sido confirmado"
                : "Tu solicitud para el {$this->date} ha sido rechazada",
        ];
    }
}
