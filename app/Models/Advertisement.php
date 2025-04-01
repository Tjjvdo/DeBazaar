<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $table = "advertisement";
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
