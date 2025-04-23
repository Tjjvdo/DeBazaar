<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renting extends Model{
    use HasFactory;

    protected $table = "renting";
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function renter()
    {
        return $this->belongsTo(User::class, 'renter_id');
    }
}