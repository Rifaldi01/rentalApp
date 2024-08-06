<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemRental extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function rental(){
        return $this->belongsTo(Rental::class);
    }
    public function items(){
        return $this->belongsTo(Item::class);
    }
}
