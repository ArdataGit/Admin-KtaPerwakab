<?php

// app/Models/Umkm.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Umkm extends Model
{
    protected $table = 'umkm';

    protected $fillable = [
        'umkm_name',
        'category',
        'logo',
        'contact_wa',
        'location',
        'description',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(UmkmProduct::class);
    }
}