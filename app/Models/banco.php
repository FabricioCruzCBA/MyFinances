<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\movimentacaofinanceira;

class banco extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bancoFamilia()
    {
        return $this->hasOne('App\Models\familia');
    }

    public function bancoMov()
    {
        return $this->hasMany(movimentacaofinanceira::class);
    }
}
