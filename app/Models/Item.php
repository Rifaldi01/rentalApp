<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = 'items';

    public function cat()
    {
        return $this->belongsTo(Category::class, 'cat_id', 'id')->withTrashed();
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'item_id');
    }

}
