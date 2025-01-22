<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtServic extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function service() {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

       public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }
}
