<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalDivisiDetail extends Model
{
    protected $guarded = [];

    public function RentalDivisi(){
        return $this->belongsTo(RentalDivisi::class);
    }
    public function accessory()
    {
        return $this->belongsTo(Accessories::class, 'accessories_id')->withTrashed();
    }
}
