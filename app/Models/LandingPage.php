<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    protected $fillable = [
        'user_id', 'slug', 'component_order', 'info_content', 'image_path', 'color',
    ];

    protected $casts = [
        'component_order' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}