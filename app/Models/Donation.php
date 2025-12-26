<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donation extends Model
{
    use HasFactory;

    protected $table = 'donations';

    protected $fillable = [
        'campaign_id',
        'user_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'is_anonymous',
        'amount',
        'status',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'amount' => 'decimal:2',
    ];

    /**
     * Relasi ke Campaign
     */
    public function campaign()
    {
        return $this->belongsTo(DonationCampaign::class, 'campaign_id');
    }

    /**
     * Relasi ke User (opsional)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ambil Tripay Transaction terkait (GLOBAL)
     */
    public function tripayTransaction()
    {
        return $this->hasOne(
            TripayTransaction::class,
            'related_id'
        )->where('transaction_type', 'donation');
    }

    /**
     * Scope: hanya donasi yang sudah dibayar
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'PAID');
    }
}
