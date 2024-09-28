<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class investimento extends Model
{
    use HasFactory;

    public function investimentoFamilia()
    {
        return $this->belongsTo('App\Models\familia');
        
    }

    public function investimentoMovimentacao()
    {
        return $this->hasMany(movimentacaoinvestimento::class);
    }

    public function investimentoCategoria(): BelongsTo
    {
        return $this->belongsTo(categoria::class, 'categoria_id', 'id');
    }

    public function investimentoSubcategoria(): BelongsTo
    {
        return $this->belongsTo(subcategoria::class, 'subcategoria_id', 'id');
    }
}
