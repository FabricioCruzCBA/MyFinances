<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class familia extends Model
{
    use HasFactory;

    public function familiaBanco()
    {
        return $this->belongsTo('App\Models\banco');
    }

    public function familiaCartao()
    {
        return $this->hasMany('App\Models\cartaocredito');
    }

    public function familiaCategoria()
    {
        return $this->hasMany('App\Models\categoria');
    }

    public function familiaCategoriaorcamento()
    {
        return $this->hasMany('App\Models\categoriaorcamento');
    }

    public function familiaDivida()
    {
        return $this->hasMany('App\Models\divida');
    }

    public function familiaInvestimento()
    {
        return $this->hasMany('App\Models\investimento');
    }

    public function familiaMovimentacaocartao()
    {
        return $this->hasMany('App\Models\movimentacaocartao');
    }

    public function familiaOrcamento()
    {
        return $this->hasMany('App\Models\orcamento');
    }

    public function familiaSubcategoria()
    {
        return $this->hasMany('App\Models\subcategoria');
    }
}
