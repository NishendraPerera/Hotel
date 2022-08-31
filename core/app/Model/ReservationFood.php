<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ReservationFood extends Model
{
    public function food(){
        return $this->belongsTo(Food::class,'food_id');
    }

    public function staff(){
        return $this->belongsTo(Staff::class,'staff_id');
    }

    public function reservation(){
        return $this->belongsTo(Reservation::class,'reservation_id');
    }
}
