<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'amount',
        'period',
        'month',
        'year',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Calculate spending for this budget
    public function getSpentAmount()
    {
        $startDate = "{$this->year}-{$this->month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        return $this->user->expenses()
            ->where('category', $this->category)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
    }

    // Get remaining budget
    public function getRemainingAmount()
    {
        return max(0, $this->amount - $this->getSpentAmount());
    }

    // Check if over budget
    public function isOverBudget()
    {
        return $this->getSpentAmount() > $this->amount;
    }

    // Get percentage used
    public function getPercentageUsed()
    {
        if ($this->amount == 0)
            return 0;
        return min(100, ($this->getSpentAmount() / $this->amount) * 100);
    }
}
