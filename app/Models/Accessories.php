<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accessories extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded =[];

    public function rental()
    {
        return $this->belongsToMany(Rental::class, 'accessories_categories')->withTrashed();
    }

    public function accessoriescategory()
    {
        return $this->hasMany(Rental::class);
    }
}
