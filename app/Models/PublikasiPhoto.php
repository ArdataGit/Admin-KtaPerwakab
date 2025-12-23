<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublikasiPhoto extends Model
{
    protected $table = 'publikasi_photo';

    protected $fillable = [
        'publikasi_id',
        'file_path'
    ];

    public function publikasi()
    {
        return $this->belongsTo(Publikasi::class);
    }
}
