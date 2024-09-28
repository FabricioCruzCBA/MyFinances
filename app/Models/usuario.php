<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class usuario extends Model
{
    use HasFactory;

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
