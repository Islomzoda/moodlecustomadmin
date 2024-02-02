<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodleClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_id',
        'moodle_id',
        'first_name',
        'last_name',
        'user_name',
        'email',
        'password',
        'tariff'
    ];
}
