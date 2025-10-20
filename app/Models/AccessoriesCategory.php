<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessoriesCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'accessories_categories';

    public function rental()
    {
        return $this->belongsTo(Rental::class)->withTrashed();
    }
    public function accessory()
    {
        return $this->belongsTo(Accessories::class, 'accessories_id')->withTrashed();
    }
}
