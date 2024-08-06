<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessoriesCategory extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'accessories_categories';

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
    public function accessory()
    {
        return $this->belongsTo(Accessories::class, 'accessories_id');
    }
}
