<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_id',
        'moodle_id',
        'first_name',
        'last_name',
        'user_name',
        'chat_id',
        'tariff',
        'price',
        'refund',
        'status',
        'comment'
    ];

    // в модели Sell
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
