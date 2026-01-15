<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'estimated_revenue',
        'estimated_expenses',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
