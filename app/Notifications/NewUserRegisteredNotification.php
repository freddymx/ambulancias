<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $newUser
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nueva solicitud de registro')
            ->greeting('Hola '.$notifiable->name)
            ->line('Un nuevo usuario ha solicitado acceso al sistema.')
            ->line('**Datos del usuario:**')
            ->line('- Nombre: '.$this->newUser->name)
            ->line('- Email: '.$this->newUser->email)
            ->line('- Teléfono: '.($this->newUser->phone ?? 'No proporcionado'))
            ->line('- ID: '.($this->newUser->identifier ?? 'No proporcionado'))
            ->action('Revisar usuario', route('filament.admin.resources.users.index'))
            ->line('Por favor, revisa la solicitud y activa la cuenta si es apropiada.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Nueva solicitud de registro',
            'message' => $this->newUser->name.' ('.$this->newUser->email.') ha solicitado acceso al sistema.',
            'link' => route('filament.admin.resources.users.index'),
        ];
    }
}
