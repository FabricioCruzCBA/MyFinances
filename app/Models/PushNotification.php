<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    //
    protected $fillable = ['usuario_id', 'subscriptions'];

    protected $casts = [
        'subscritions' => 'array'
    ];
}
