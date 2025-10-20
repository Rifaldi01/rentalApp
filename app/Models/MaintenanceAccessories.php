<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceAccessories extends Model
{
   protected $guarded=[];

   public function accessories(){
       return $this->belongsTo(Accessories::class,'accessories_id','id');
   }
}
