<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class KtaTemplate
 *
 * Represents KTA template data (front & back image).
 *
 * @property int $id
 * @property string $name
 * @property string $front_image
 * @property string $back_image
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class KtaTemplate extends Model
{
    protected $table = 'kta_templates';

    protected $fillable = [
        'name',
        'front_image',
        'back_image',
        'is_active'
    ];
}