<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; // <-- obrigatório


class usuario extends Model
{
    use HasFactory, Notifiable; // <--- aqui

   

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
