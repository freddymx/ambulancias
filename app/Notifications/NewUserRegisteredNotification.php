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
            ->subject(__('app.notifications.new_user_title'))
            ->greeting(__('filament-support::actions/greetings.hello', ['name' => $notifiable->name]))
            ->line(__('app.notifications.new_user_line1'))
            ->line('**'.__('app.notifications.user_data').'**')
            ->line('- '.__('validation.attributes.name').': '.$this->newUser->name)
            ->line('- '.__('app.users.email').': '.$this->newUser->email)
            ->line('- '.__('validation.attributes.phone').': '.($this->newUser->phone ?? __('app.notifications.not_provided')))
            ->line('- '.__('app.users.dni').': '.($this->newUser->dni ?? __('app.notifications.not_provided')))
            ->action(__('app.notifications.review_user'), route('filament.admin.resources.users.index'))
            ->line(__('app.notifications.review_request'));
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => __('app.notifications.new_user_title'),
            'message' => __('app.notifications.new_user_message', ['name' => $this->newUser->name, 'email' => $this->newUser->email]),
            'link' => route('filament.admin.resources.users.index'),
        ];
    }
}
