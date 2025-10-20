<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $guarded = [];

    public function accessories(){
        return $this->belongsTo(Accessories::class)->withTrashed();
    }
}
