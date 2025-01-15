<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debts extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function rental() {
        return $this->belongsTo(Rental::class, 'rental_id');
    }

    public function sale()
    {
        return $this->belongsTo(Rental::class, 'sale_id', 'id');
    }
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }
}
