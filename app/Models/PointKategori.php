<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointKategori extends Model
{
    use HasFactory;

    protected $table = 'point_kategori'; // Sesuaikan nama tabel jika diubah
    protected $fillable = [
        'name',
        'point',
    ];

    protected $casts = [
        'point' => 'integer',
    ];

    // Relasi: Satu kategori bisa punya banyak user_point
    public function userPoints()
    {
        return $this->hasMany(UserPoint::class, 'id_category', 'id');
    }
}