<?php

namespace App\Services;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use App\Models\PushSubscription;

class PushService
{
    public static function sendNotification($usuarioId, $message)
    {
        // Configuração VAPID
        $auth = [
            'VAPID' => [
                'subject' => 'mailto:seuemail@dominio.com', // seu e-mail
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        $webPush = new WebPush($auth);

        // Pega todas as subscriptions do usuário
        $subscriptions = PushSubscription::where('usuario_id', $usuarioId)->get();

        foreach ($subscriptions as $sub) {
            $subscription = Subscription::create([
                'endpoint' => $sub->endpoint,
                'publicKey' => $sub->public_key,
                'authToken' => $sub->auth_token,
            ]);

            // Envia a notificação
            $webPush->queueNotification(
                $subscription,
                json_encode($message)
            );
        }

        // Confirmação do envio
        foreach ($webPush->flush() as $report) {
            if ($report->isSuccess()) {
                \Log::info("Mensagem enviada para {$report->getRequest()->getUri()}");
            } else {
                \Log::error("Falha no envio: {$report->getReason()}");
            }
        }
    }
}
