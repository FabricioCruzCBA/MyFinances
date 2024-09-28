<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\movimentacaofinanceira;
use App\Models\categoriaorcamento;

class categoria extends Model
{
    protected $guarded = [];
    
    use HasFactory;

    public function categoriaFamilia()
    {
        return $this->belongsTo('App\Models\familia');
    }

    public function categoriaCategoriaorcamento()
    {
        return $this->hasMany(categoriaorcamento::class);
    }

    public function categoriaMovCard()
    {
        return $this->hasMany('App\Models\movimentacaocartao');
    }

    public function categoriaSubcategoria()
    {
        return $this->hasMany('App\Models\subcategoria');
    }

    public function categoriaItensOrcamento()
    {
        return $this->hasMany(categoriaorcamento::class);
    }

    public function categoriaMovFin()
    {
        return $this->hasMany(movimentacaofinanceira::class);
    }
}
