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
        'status',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // Scope untuk filter berdasarkan status
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(UmkmProductPhoto::class, 'product_id');
    }
}
