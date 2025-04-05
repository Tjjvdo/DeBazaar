<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model{
    use HasFactory;

    protected $table = "bid";
    protected $guarded = [];

    public $timestamps = false;

    public function bidder()
    {
        return $this->belongsTo(User::class, 'bidder_id');
    }

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'advertisement_id');
    }
}