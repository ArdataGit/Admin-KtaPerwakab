<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NewsArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'content',
        'cover_image',
        'video_url',
        'author_id',
    ];


    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // akses URL cover image
    public function getCoverUrlAttribute()
    {
        return $this->cover_image
            ? asset('storage/news/' . $this->cover_image)
            : null;
    }
}
