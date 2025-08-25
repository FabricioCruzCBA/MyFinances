<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    use HasFactory;

    protected $table = 'push_subscriptions';

    protected $fillable = [
        'usuario_id',
        'endpoint',
        'public_key',
        'auth_token',
    ];
}
