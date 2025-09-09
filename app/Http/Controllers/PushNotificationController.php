<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PushNotification;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\agenda;

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

    public function testeNotificacao()
    {
        //pegar as assinaturas de notificação
        $assinaturas = PushNotification::all();

        //pegar os usuarios
        $user = PushNotification::pluck('usuario_id')->all();
        // Define o novo intervalo de tempo para checar: agora até os próximos 15 minutos
        // Use startOfSecond() para zerar os milissegundos e segundos
        $now = Carbon::now()->startOfMinute();
        $in15Minutes = $now->copy()->addMinutes(15);
        //dd($user);
        //Pegando os compromissos de quem tem assintatura
        $agenda = agenda::whereIn('usuario_id', $user)
                        ->where('Ativo', '1')
                        ->where('DataStart',$in15Minutes)
                        ->where('Confirmacao', '0')
                        ->get();
        
        //autenticando a assinatura       
        $auth = [
            'VAPID' => [
                'subject' => 'https://app.sistemadiesel.com.br/home',
                'publicKey' => "BNINyJ7UM0DgiGpu3hMrgHHHnbyA3eQbHUtLT0Sf21DCsr7VY0ENDvtGeqnUF02xVAqK60xwsrSbai_7jwcXo-k",
                'privateKey' => "owBEECsI1l6L65VKZ71BsrL8eUyqEceO3NK4bpO_UbE"
            ],
        ];
        //adicionando a autenticação no WebPush
        $webPush = new WebPush($auth);
        //dd($agenda);
        echo($now.'<br>');
        echo($in15Minutes);
        if(!empty($agenda)){
            foreach($agenda as $not){
                $assinatura = $assinaturas->where('usuario_id', $not->usuario_id)->first();
                //dd($assinatura);
                $valor = json_decode($assinatura->subscriptions, true);

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
                        'payload' => '{"title":"Você tem agenda em 15min.", "body":"Compromisso:'.$not->Descricao.'", "url":"https://app.sistemadiesel.com.br/login"}',
                        
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

                echo('deu bom');
                dd($agenda);
            }
        }else{
            echo('deu ruim');
            dd($agenda);
        }
        
        dd($agenda);
    }
}
