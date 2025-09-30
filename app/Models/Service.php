<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function debtService(){
        return $this->hasMany(DebtServic::class, 'service_id', 'id');
    }
    public function cust()
    {
        return $this->belongsTo(Customer::class, 'customer_id', "id")->withTrashed();
    }
}
