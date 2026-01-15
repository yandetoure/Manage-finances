<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'amount',
        'payment_date',
        'note',
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
