<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationHistory extends Model
{
    protected $table = 'organization_histories';

    protected $fillable = [
        'title',
        'content',
        'featured_image',
        'meta_description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}