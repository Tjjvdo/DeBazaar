<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renting extends Model{
    use HasFactory;

    protected $table = "renting";
    protected $guarded = [];

    public $timestamps = false;

    public function bidder()
    {
        return $this->belongsTo(User::class, 'renter_id');
    }
}