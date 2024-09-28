<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tokenverificaemail extends Model
{
    use HasFactory;

    public function tokenUsuario()
    {
        return $this->belongsTo('App\Models\usuario');
    }
}
