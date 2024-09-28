<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class faturacartao extends Model
{
    use HasFactory;

    public function faturaCartao()
    {
        return $this->belongsTo('App\Models\cartaocredito', 'cartaocredito_id', 'id');
    }
}
