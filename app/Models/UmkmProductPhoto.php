<?php

// app/Models/UmkmProductPhoto.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UmkmProductPhoto extends Model
{
    protected $table = 'umkm_product_photo';

    protected $fillable = [
        'product_id',
        'file_path',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(UmkmProduct::class, 'product_id');
    }
}
