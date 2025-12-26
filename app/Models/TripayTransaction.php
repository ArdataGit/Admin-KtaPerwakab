<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TripayTransaction extends Model
{
    use HasFactory;

    protected $table = 'tripay_transactions';

    protected $fillable = [
        'merchant_ref',
        'tripay_reference',
        'payment_method',
        'payment_name',
        'amount',
        'fee_customer',
        'fee_merchant',
        'total_amount',
        'status',
        'transaction_type',
        'related_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'tripay_payload',
        'paid_at',
        'expired_at',
        'is_dev',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee_customer' => 'decimal:2',
        'fee_merchant' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'tripay_payload' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'is_dev' => 'boolean',
    ];

    /**
     * Relasi ke User (jika ada)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi manual ke Donation
     * (karena Tripay bersifat global)
     */
    public function donation()
    {
        return $this->belongsTo(
            Donation::class,
            'related_id'
        )->where('transaction_type', 'donation');
    }

    /**
     * Scope status PAID
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'PAID');
    }

    /**
     * Scope berdasarkan tipe transaksi
     */
    public function scopeType($query, string $type)
    {
        return $query->where('transaction_type', $type);
    }
}
