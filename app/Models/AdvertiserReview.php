<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertiserReview extends Model
{
    protected $table = "advertiser_review";
    protected $guarded = [];
    public $timestamps = false;

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function advertiser()
    {
        return $this->belongsTo(User::class, 'advertiser_id');
    }
}
