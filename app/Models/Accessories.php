<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessories extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function rental()
    {
        return $this->belongsToMany(Rental::class, 'accessories_categories');
    }

    public function accessoriescategory()
    {
        return $this->hasMany(Rental::class);
    }
}
