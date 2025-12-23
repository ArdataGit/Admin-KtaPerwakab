<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TukarPoint extends Model
{
    protected $fillable = [
        'user_id',
        'master_penukaran_poin_id',
        'point',
        'tanggal',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function masterPenukaran()
    {
        return $this->belongsTo(MasterPenukaranPoin::class, 'master_penukaran_poin_id');
    }
}
