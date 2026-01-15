<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Saving extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_name',
        'target_amount',
        'current_amount',
        'deadline',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contributions()
    {
        return $this->hasMany(SavingContribution::class);
    }
}
