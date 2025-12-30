<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'birth_date',
        'age',
        'address',
        'city',
        'occupation',
        'join_date',
        'profile_photo',
        'status',
        'role',
        'password',
        'expired_at',
        'point',
        'kta_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'join_date' => 'date',
            'expired_at' => 'date',
            'age' => 'integer',
            'point' => 'integer',
        ];
    }

    /**
     * Relasi: User punya banyak UserPoint (history point).
     */
    public function userPoints(): HasMany
    {
        return $this->hasMany(UserPoint::class, 'id_user');
    }

    /**
     * Relasi: User punya satu UMKM.
     */
    public function umkm(): HasOne
    {
        return $this->hasOne(Umkm::class);
    }
}