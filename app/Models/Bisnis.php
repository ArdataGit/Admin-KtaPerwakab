<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bisnis extends Model
{
    protected $table = 'bisnis';

    protected $fillable = [
        'nama', 'slug', 'deskripsi', 'kategori',
        'alamat', 'telepon', 'email', 'website', 'is_active'
    ];

    public function media()
    {
        return $this->hasMany(BisnisMedia::class);
    }

    public function images()
    {
        return $this->hasMany(BisnisMedia::class)->where('type', 'image');
    }

    public function videos()
    {
        return $this->hasMany(BisnisMedia::class)
                    ->whereIn('type', ['video', 'youtube', 'embed']);
    }
}


