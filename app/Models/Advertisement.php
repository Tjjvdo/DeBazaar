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
        'inactive_at' => 'datetime',
    ];

    public function biddings()
    {
        return $this->hasMany(Bid::class);
    }
    
    public function rentings()
    {
        return $this->hasMany(Renting::class);
    }
}
