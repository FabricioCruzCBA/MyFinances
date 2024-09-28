<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\movimentacaofinanceira;


class subcategoria extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subMovFin()
    {
        return $this->hasMany(movimentacaofinanceira::class);
    }

    public function subcategoriaFamilia()
    {
        return $this->belongsTo(familia::class);
    }

    public function subcategoriaCategoria(): BelongsTo
    {
        return $this->belongsTo(categoria::class, 'categoria_id', 'id');
    }
}
