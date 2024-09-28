<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categoriaorcamento extends Model
{
    use HasFactory;

    public function categoriaorcamentoOrcamento()
    {
        return $this->belongsTo(orcamento::class, 'orcamento_id', 'id');
    }

    public function categoriaorcamentoFamilia()
    {
        return $this->belongsTo('App\Models\familia');
    }

    public function categoriaorcamentoCategoria()
    {
        return $this->belongsTo(categoria::class, 'categoria_id', 'id');
    }
}
