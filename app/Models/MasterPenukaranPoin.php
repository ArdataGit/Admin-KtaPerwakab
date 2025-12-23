<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPenukaranPoin extends Model
{
    use SoftDeletes;

    protected $table = 'master_penukaran_poin';

    protected $fillable = [
        'produk',
        'keterangan',
        'image',
        'jumlah_poin',
        'is_active',
    ];
}

