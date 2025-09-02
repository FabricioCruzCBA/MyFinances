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

        
        $payload = json_encode([
            "endpoint" => "https://fcm.googleapis.com/fcm/send/dy60voc6eW4:APA91bGzbJIP9wEsJMtXGjn3963Owic0QQPQHpfrz5fZBfXNGpxmcZxca_FWfR9suVP3nLOYUCSTSu1NhHv_i4uCFkxSMC9o8Tus35YJo_UdYfyW9-URPyaky_YKivsJ2pqiAEgAvLwK",
                    "keys" => [
                        'p256dh' => 'BBdZ7fJwk8q_sqb42C8AaqO1bSXuqDfa3bA5lGg7_KTFUTh7a0PJly-q1MvlThzn8jkTN--hariKG3rEvi9IIg0',
                        'auth' => 'QzI2qXuW9wh9Wtk5GiUuBw'
                    ],
        ]);

        $assinaturas = PushNotification::all();

        
        $subscription = Subscription::create(json_decode($payload, true));

        // array of notifications
        $notifications = [
            [
                'subscription' => $subscription,
                'payload' => '{"title":"Notificação teste!", "body":"Essa é uma notificação teste"}',
            ],
        ];

        
        // send multiple notifications with payload
        foreach ($notifications as $notification) {
            $webPush->queueNotification(
                $notification['subscription'],
                $notification['payload'] // optional (defaults null)
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
