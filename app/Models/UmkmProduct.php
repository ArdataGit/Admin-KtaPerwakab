<?php

// app/Models/UmkmProduct.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UmkmProduct extends Model
{
    protected $table = 'umkm_product';

    protected $fillable = [
        'umkm_id',
        'product_name',
        'price',
        'description',
        'youtube_link',
    ];

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(UmkmProductPhoto::class, 'product_id');
    }
}
