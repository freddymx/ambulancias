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
        $isAffectedUser = $notifiable->id === $this->shift->user_id;
        $url = $notifiable->isAdmin()
            ? url("/admin/ambulance-shifts/{$this->shift->id}")
            : url('/admin/ambulance-shifts');

        $subject = $this->getSubject($isAffectedUser);
        $body = $this->getBody($isAffectedUser);

        return (new MailMessage)
            ->subject($subject)
            ->line($body)
            ->action('Ver Turno', $url);
    }

    private function getSubject(bool $isAffectedUser): string
    {
        if ($isAffectedUser) {
            return 'Actualización de tu turno';
        }

        return 'Actualización de turno';
    }

    private function getBody(bool $isAffectedUser): string
    {
        $date = $this->shift->date->format('d/m/Y');
        $statusMessage = $this->getStatusMessage();

        if ($isAffectedUser) {
            return "Hola {$this->shift->user->name}, {$statusMessage}";
        }

        return "El turno del usuario {$this->shift->user->name} para el día {$date} ha sido actualizado. Estado actual: {$this->shift->status->value}";
    }

    private function getStatusMessage(): string
    {
        $date = $this->shift->date->format('d/m/Y');
        $status = $this->shift->status;

        if (isset($this->changes['status'])) {
            return match ($status) {
                \App\Enums\ShiftStatus::Accepted => "tu turno para el día {$date} ha sido aceptado.",
                \App\Enums\ShiftStatus::Rejected => "tu turno para el día {$date} ha sido rechazado. Por favor, contacta con nosotros para más información.",
                \App\Enums\ShiftStatus::EnReserva => "tu turno para el día {$date} está en reserva. Te notificaremos cuando sea confirmado.",
                \App\Enums\ShiftStatus::Pending => "tu turno para el día {$date} está pendiente de confirmación.",
            };
        }

        return "tu turno para el día {$date} ha sido actualizado.";
    }

    public function toDatabase(object $notifiable): array
    {
        $isAffectedUser = $notifiable->id === $this->shift->user_id;
        $title = $isAffectedUser ? 'Tu turno ha sido actualizado' : 'Turno actualizado';
        $body = $this->getBody($isAffectedUser);

        return FilamentNotification::make()
            ->title($title)
            ->body($body)
            ->actions([
                Action::make('view')
                    ->label('Ver')
                    ->url($notifiable->isAdmin() ? "/admin/ambulance-shifts/{$this->shift->id}" : '/admin/ambulance-shifts'),
            ])
            ->getDatabaseMessage();
    }
}
