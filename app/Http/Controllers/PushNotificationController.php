<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PushNotification;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotificationController extends Controller
{
    //
    public function saveSubscription(Request $request)
    {
        //dd($request->sub);
        $items = new PushNotification;
        $items->usuario_id = session('user');
        $items->subscriptions = $request->sub;
        $items->save();

        return response()->json(['massage'=>'deu certo'], 200);
    }

    public function sendNotification()
    {
        $auth = [
            'VAPID' => [
                'subject' => 'https://app.sistemadiesel.com.br/home',
                'publicKey' => "BNINyJ7UM0DgiGpu3hMrgHHHnbyA3eQbHUtLT0Sf21DCsr7VY0ENDvtGeqnUF02xVAqK60xwsrSbai_7jwcXo-k",
                'privateKey' => "owBEECsI1l6L65VKZ71BsrL8eUyqEceO3NK4bpO_UbE"
            ],
        ];

        $webPush = new WebPush($auth);


        $assinaturas = PushNotification::all();
        
        
        foreach($assinaturas as $assinatura){
            $valor = json_decode($assinatura->subscriptions, true);
            echo($valor['endpoint']);
            echo('<br>');
            echo($valor['keys']['p256dh']);
            //dd($valor);
            $asing = json_encode([
                "endpoint" => $valor['endpoint'],
                "keys" => [
                    'p256dh' => $valor['keys']['p256dh'], 
                    'auth' => $valor['keys']['auth']
                ]
            ]);

            $subscription = Subscription::create(json_decode($asing, true));   

            // array of notifications
            $notifications = [
                [
                    'subscription' => $subscription,
                    'payload' => '{"title":"Notificação teste!", "body":"Essa é uma notificação teste", "url":"https://app.sistemadiesel.com.br/login"}',
                    
                ],
            ];

            // send multiple notifications with payload
            foreach ($notifications as $notification) {
                $webPush->queueNotification(
                    $notification['subscription'],
                    $notification['payload']
                );
            }

            /**
             * Check sent results
             * @var MessageSentReport $report
             */
            foreach ($webPush->flush() as $report) {
                $endpoint = $report->getRequest()->getUri()->__toString();

                if ($report->isSuccess()) {
                    echo "[v] Message sent successfully for subscription {$endpoint}.";
                } else {
                    echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
                }
            }
        }
    }
}
