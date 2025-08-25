<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;

class TestNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['webpush']; // <- ESSENCIAL
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Teste de Notificação')
            ->body('Essa é uma notificação de teste')
            ->action('Visualizar', 'view_action');
    }
}
