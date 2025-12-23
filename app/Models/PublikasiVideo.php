<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublikasiVideo extends Model
{
    protected $table = 'publikasi_video';

    protected $fillable = [
        'publikasi_id',
        'link'
    ];

    public function publikasi()
    {
        return $this->belongsTo(Publikasi::class);
    }
}
