<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orcamento extends Model
{
    use HasFactory;

    public function orcamentoCategoriaorcamento()
    {
        return $this->hasMany('App\Models\categoriaorcamento');
    }

    public function orcamentoFamilia()
    {
        return $this->belongsTo('App\Models\familia');
    }

    public function orcamentoMovFin()
    {
        return $this->hasMany(movimentacaofinanceira::class);
    }
}
