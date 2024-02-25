<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mpstat extends Model
{
    use HasFactory;

    protected $fillable = [
        'mpstats_id',
        'login',
        'password',
        'expire_at',
        'api_key',
        'app_link',
        'telegram_id'
    ];
}
