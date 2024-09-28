<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class acessosusuario extends Model
{
    use HasFactory;

    public function acessoUsuario()
    {
        return $this->belongsTo('App\Models\usuario');
    }
}
