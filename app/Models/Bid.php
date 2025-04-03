<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model{
    protected $table = "bid";
    protected $guarded = [];

    public $timestamps = false;
}