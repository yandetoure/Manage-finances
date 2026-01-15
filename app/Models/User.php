<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    public function debts()
    {
        return $this->hasMany(Debt::class);
    }
    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
    public function savings()
    {
        return $this->hasMany(Saving::class);
    }
    public function forecasts()
    {
        return $this->hasMany(Forecast::class);
    }
    public function moduleSettings()
    {
        return $this->hasMany(ModuleSetting::class);
    }
    public function settings()
    {
        return $this->hasOne(UserSetting::class);
    }

    public function getCurrencyAttribute()
    {
        return $this->settings->currency ?? 'FCFA';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
