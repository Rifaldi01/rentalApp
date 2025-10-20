<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentalDivisi extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    public function divisi(){
        return $this->belongsTo(Divisi::class);
    }
    public function RentalDivisiDetail(){
        return $this->hasMany(RentalDivisiDetail::class,'rental_divisi_id', 'id');
    }
}
