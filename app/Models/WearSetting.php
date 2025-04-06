<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WearSetting extends Model
{
    protected $table = "wear_setting";
    protected $guarded = [];

    public $timestamps = false;

    public function advertisement()
    {
        return $this->belongsTo(advertisement::class);
    }
}
