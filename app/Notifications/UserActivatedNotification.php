<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserActivatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $activatedBy
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tu cuenta ha sido activada')
            ->greeting('¡Hola '.$notifiable->name.'!')
            ->line('Tu cuenta en el sistema de ambulancias ha sido activada.')
            ->line('Ahora puedes acceder al panel y solicitar turnos.')
            ->action('Ir al panel', url('/admin'))
            ->line('Si tienes alguna pregunta, no dudes en contactar con nosotros.');
    }
}
