<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\agenda;
use App\Models\PushNotification;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Carbon\Carbon;



class NotificacaoAgenda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notificacao-agenda';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //pegar as assinaturas de notificação
        $assinaturas = PushNotification::all();

        //pegar os usuarios
        $user = PushNotification::pluck('usuario_id')->all();
        // Define o novo intervalo de tempo para checar: agora até os próximos 15 minutos
        // Use startOfSecond() para zerar os milissegundos e segundos
        $now = Carbon::now()->startOfSecond();
        $in15Minutes = $now->copy()->addMinutes(15);

        //Pegando os compromissos de quem tem assintatura
        $agenda = agenda::whereInd('usuario_id', $user)
                        ->where('Ativo', '1')
                        ->whereBetween('DataStart', [$now, $in15Minutes])
                        ->where('Confirmacao', '0');
        
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

        if(count($agenda)>0){
            foreach($agenda as $not){
                $assinatura = $assinaturas->where('usuario_id', $agenda->usuario_id);
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

                $this->info('Executado');

            }
        }else{
            $this->info('Não há agendamentos');
        }



    }
}
