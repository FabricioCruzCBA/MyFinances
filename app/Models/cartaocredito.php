<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cartaocredito extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cartaoFamilia ()
    {
        return $this->belongsTo('App\Models\familia');
    }

    public function cartaoMovimentacao()
    {
        return $this->hasMany('App\Models\movimentacaocartao');
    }

    public function cartaoFatura()
    {
        return $this->hasMany('App\Models\faturacartao');
    }
}
