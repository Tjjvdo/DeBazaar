<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisementRelated extends Model
{
    protected $table = "advertisement_related";
    protected $guarded = [];
    public $timestamps = false;

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function relatedAdvertisement()
    {
        return $this->belongsTo(Advertisement::class, 'related_advertisement_id');
    }
}
