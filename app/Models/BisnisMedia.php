<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BisnisMedia extends Model
{
    protected $table = 'bisnis_media';

    protected $fillable = [
        'bisnis_id',
        'type',
        'file_path',
        'url',
        'thumbnail',
        'urutan'
    ];

    public function bisnis()
    {
        return $this->belongsTo(Bisnis::class);
    }
}


