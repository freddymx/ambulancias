<?php

namespace App\Notifications;

use App\Models\AmbulanceShift;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AmbulanceShiftUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public AmbulanceShift $shift,
        public array $changes = []
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = $notifiable->isAdmin()
            ? url("/admin/ambulance-shifts/{$this->shift->id}")
            : url("/admin/ambulance-shifts"); // Users might verify via calendar or list

        return (new MailMessage)
            ->subject('Actualización de turno')
            ->line("El turno del usuario {$this->shift->user->name} para el día {$this->shift->date->format('d/m/Y')} ha sido actualizado.")
            ->line("Estado actual: " . $this->shift->status->value)
            ->action('Ver Turno', $url);
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Turno actualizado')
            ->body("El turno del usuario {$this->shift->user->name} para el día {$this->shift->date->format('d/m/Y')} ha sido actualizado.")
            ->actions([
                Action::make('view')
                    ->label('Ver')
                    ->url($notifiable->isAdmin() ? "/admin/ambulance-shifts/{$this->shift->id}" : "/admin/ambulance-shifts"),
            ])
            ->getDatabaseMessage();
    }
}
