<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publikasi extends Model
{
    protected $table = 'publikasi';

    protected $fillable = [
        'title',
        'creator',
        'description'
    ];

    public function photos()
    {
        return $this->hasMany(PublikasiPhoto::class);
    }

    public function videos()
    {
        return $this->hasMany(PublikasiVideo::class);
    }
}

