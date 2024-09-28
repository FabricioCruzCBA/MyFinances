<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class meta extends Model
{
    use HasFactory;

    public function metaMovimentacao()
    {
        return $this->hasMany(movimentacaometa::class);
    }
}
