<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Logging extends Model
{
    protected $table = 'logs';

    public function user(){
        return $this->belongsTo(Admin::class,'user_id');
    }

    public function reservation(){
        return $this->belongsTo(Reservation::class,'reservation_id');
    }
}
