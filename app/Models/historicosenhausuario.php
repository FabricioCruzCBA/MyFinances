<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class historicosenhausuario extends Model
{
    use HasFactory;

    public function historicosenhaUsuario() 
    {
        return $this->belongsTo('App\Models\usuario');
        
    }
}
