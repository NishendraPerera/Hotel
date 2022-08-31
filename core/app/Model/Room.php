<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;
    public function type(){
        return $this->belongsTo(RoomType::class,'room_type_id');
    }
    public function floor(){
        return $this->belongsTo(Floor::class,'floor_id');
    }
    public function reservedRoom(){
        return $this->hasMany(ReservationNight::class,'room_id');
    }
    public function available($date){
        return  ReservationNight::where('room_id',$this->id)->whereHas('reservation',function ($q){
           $q->whereNotIn('status',['CANCEL','ONLINE_PENDING']);
       })->where('date',$date)->first();
    }

    public function available_new($date){

    	return ReservationNight::join('rooms', 'rooms.id', '=', 'reservation_nights.room_id')
            ->join('reservations', 'reservation_nights.reservation_id', '=', 'reservations.id')
            ->where('rooms.number',$this->number)
            ->whereHas('reservation',function ($q){
                $q->whereNotIn('status',['CANCEL','ONLINE_PENDING']);
            })
        ->where(function($q1) use ($date)
        {
            $q1->where(function($q2) use ($date){
                $q2->where('reservation_nights.check_in', '<=', date('Y-m-d H:i:s', strtotime($date)))->where('reservation_nights.check_out', '>', date('Y-m-d H:i:s', strtotime($date)));
                $q2->where('reservations.check_out', '>', date('Y-m-d H:i:s', strtotime($date)))->where('reservations.status', '!=', 'COMPLETED');
            });
            $q1->orWhere(function($q3) use ($date){
                $q3->where('reservation_nights.check_in', '<=', date('Y-m-d H:i:s', strtotime($date)))->where('reservations.check_out', '<=', date('Y-m-d H:i:s', strtotime($date)))->where('reservations.status', '!=', 'COMPLETED');
            });
        })->first();
    
       //  return ReservationNight::join('rooms', 'rooms.id', '=', 'reservation_nights.room_id')
       //  ->join('reservations', 'reservation_nights.reservation_id', '=', 'reservations.id')
       //  ->where('rooms.number',$this->number)
       //  ->whereHas('reservation',function ($q){
       //     $q->whereNotIn('status',['CANCEL','ONLINE_PENDING']);
       // })->where('reservation_nights.check_in', '<=', date('Y-m-d H:i:s', strtotime($date)))->where('reservation_nights.check_out', '>', date('Y-m-d H:i:s', strtotime($date)))
       // ->where('reservations.check_out', '>', date('Y-m-d H:i:s', strtotime($date)) )->first();

    //    return ReservationNight::join('rooms', 'rooms.id', '=', 'reservation_nights.room_id')->where('rooms.number',$this->number)->whereHas('reservation',function ($q){
    //     $q->whereNotIn('status',['CANCEL','ONLINE_PENDING']);
    // })->where('check_in', '<=', date('Y-m-d H:i:s', strtotime($date)))->where('check_out', '>', date('Y-m-d H:i:s', strtotime($date)))->first();

    }
}
