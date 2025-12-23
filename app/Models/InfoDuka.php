<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoDuka extends Model
{
    protected $table = 'info_duka';

    protected $fillable = [
        'nama_almarhum',
        'usia',
        'asal',
        'foto',
        'tanggal_wafat',
        'tanggal_publish',
        'judul',
        'isi',
        'rumah_duka',
        'alamat_rumah_duka',
        'jenis_pemakaman',
        'lokasi_pemakaman',
        'waktu_pemakaman',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'tanggal_wafat' => 'date',
        'tanggal_publish' => 'datetime',
        'waktu_pemakaman' => 'datetime',
        'is_active' => 'boolean',
    ];
}
