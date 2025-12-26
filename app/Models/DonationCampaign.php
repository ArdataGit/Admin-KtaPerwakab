<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DonationCampaign extends Model
{
    use HasFactory;

    protected $table = 'donation_campaigns';

    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'start_date',
        'end_date',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];
    protected $appends = [
        'total_collected',
    ];

    /**
     * Relasi ke donations
     */
    public function donations()
    {
        return $this->hasMany(Donation::class, 'campaign_id');
    }
    /**
     * TOTAL DONASI TERKUMPUL (HANYA PAID)
     */
    public function getTotalCollectedAttribute()
    {
        return $this->donations()
            ->whereHas('tripayTransaction', function ($q) {
                $q->where('status', 'PAID');
            })
            ->sum('amount');
    }
}
