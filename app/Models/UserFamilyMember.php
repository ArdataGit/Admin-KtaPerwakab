<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFamilyMember extends Model
{
    use HasFactory;

    protected $table = 'user_family_members';

    protected $fillable = [
        'user_id',
        'relationship',
        'birth_date',
        'name_ktp',
        'nickname',
        'address',
    ];

    /**
     * Relationship: Family member belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}