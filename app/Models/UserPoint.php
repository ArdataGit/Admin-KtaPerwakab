<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    use HasFactory;

    protected $table = 'user_point'; // Sesuaikan nama tabel jika diubah
    protected $fillable = [
        'id_category',
        'id_user',
        'created_by',
    ];

    protected $casts = [
        'id_category' => 'integer',
        'id_user' => 'integer',
        'created_by' => 'integer',
    ];

    // Relasi: belongsTo PointKategori
    public function pointKategori()
    {
        return $this->belongsTo(PointKategori::class, 'id_category', 'id');
    }

    // Relasi: belongsTo User (untuk id_user)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Relasi: belongsTo User (untuk created_by)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // Relasi: Satu user bisa punya banyak user_point
    public function scopeByUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }
}