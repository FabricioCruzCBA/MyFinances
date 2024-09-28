<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class movimentacaofinanceira extends Model
{
    use HasFactory;

    public function movCategoria()
    {
        return $this->belongsTo(categoria::class, 'categoria_id', 'id');
    }

    public function movSubcategoria()
    {
        return $this->belongsTo(subcategoria::class, 'subcategoria_id', 'id');
    }

    public function movBanco()
    {
        return $this->belongsTo(banco::class, 'banco_id', 'id');
    }
}
