<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = ['accessories_id'];

    public function accessoriescategory()
    {
        return $this->hasMany(AccessoriesCategory::class);
    }
   protected $dates = ['date_start', 'date_end', 'created_at', 'updated_at'];

    public function cust()
    {
        return $this->belongsTo(Customer::class, 'customer_id', "id");
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', "id");
    }
    public function access()
    {
        return $this->belongsToMany(Accessories::class, 'accessories_categories', 'rental_id', 'accessories_id');
    }
    public function debt()
    {
        return $this->hasMany(Debts::class, 'rental_id', 'id');
    }


}
