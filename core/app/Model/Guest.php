<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    public function reservation(){
        return $this->belongsTo(Reservation::class,'reservation_id');
    }
}
