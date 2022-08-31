<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ReservationPaidService extends Model
{
    public function service(){
        return $this->belongsTo(PaidService::class,'pad_service_id');
    }

    public function staff(){
        return $this->belongsTo(Staff::class,'staff_id');
    }

    public function reservation(){
        return $this->belongsTo(Reservation::class,'reservation_id');
    }
}
