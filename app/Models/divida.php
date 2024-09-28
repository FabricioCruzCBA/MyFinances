<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class divida extends Model
{
    use HasFactory;

    public function dividaFamilia()
    {
        return $this->belongsTo('App\Models\familia');
    }

    public function dividaMovimentacaodivida()
    {
        return $this->hasMany(movimentacaodivida::class);
    }

    public function dividaCategoria(): BelongsTo
    {
        return $this->belongsTo(categoria::class, 'categoria_id', 'id');
    }

    public function dividaSubcategoria(): BelongsTo
    {
        return $this->belongsTo(subcategoria::class, 'subcategoria_id', 'id');
    }
}
