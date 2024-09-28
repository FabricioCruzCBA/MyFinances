<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class movimentacaocartao extends Model
{
    use HasFactory;

    public function movimentacaoCartao() 
    {
        return $this->belongsTo('App\Models\cartaocredito');   
    }

    public function movimentacaocartaoFamilia()
    {
        return $this->belongsTo('App\Models\familia');
    }

    public function movcardCategoria()
    {
        return $this->belongsTo('App\Models\categoria', 'categoria_id','id');
    }

    public function movcardSub()
    {
        return $this->belongsTo('App\Models\subcategoria', 'subcategoria_id', 'id');
    }

}
