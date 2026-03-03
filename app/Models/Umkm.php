<?php

// app/Models/Umkm.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Umkm extends Model
{
    protected $table = 'umkm';

    protected $fillable = [
        'user_id',
        'category',
        'logo',
        'contact_wa',
        'location',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(UmkmProduct::class);
    }
}