<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; // <-- obrigatório


class Usuario extends Model
{
    use HasFactory, Notifiable; // <--- aqui

    public function pushSubscriptions()
    {
        return $this->hasMany(\NotificationChannels\WebPush\PushSubscription::class, 'usuario_id');
    }

    public function webPushSubscriptions()
    {
        return $this->hasMany('App\Models\PushSubscription');
    }
   

    // Necessário para WebPushChannel
    public function routeNotificationForWebPush()
    {
        return $this->pushSubscriptions();
    }

    public function usuarioAcesso()
    {
        return $this->hasMany('App\Models\acessosusuario');
    }

    public function usuarioHistoricosenha() 
    {
        return $this->hasMany('App\Models\historicosenhausuario');
    }

    public function usuarioToken()
    {
        return $this->hasMany('App\Models\tokenverificaemail');
    }
}
