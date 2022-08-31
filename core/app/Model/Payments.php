<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    public function reservation(){
        return $this->belongsTo(Gateway::class,'gateway_id');
    }
}
