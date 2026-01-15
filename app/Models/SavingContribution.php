<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Saving;

class SavingContribution extends Model
{
    protected $fillable = [
        'saving_id',
        'amount',
        'contribution_date',
        'notes',
    ];

    public function parentSaving()
    {
        return $this->belongsTo(Saving::class, 'saving_id');
    }
}
