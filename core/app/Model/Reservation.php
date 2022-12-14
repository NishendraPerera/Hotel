<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    public function roomType(){
        return $this->belongsTo(RoomType::class,'room_type_id');
    }
    public function statusClass(){
        if($this->status === 'PENDING'){
            return 'warning';
        }elseif($this->status === 'CANCEL'){
            return 'danger';
        }elseif($this->status === 'SUCCESS'){
            return 'success';
        }elseif($this->status === 'ONLINE_PENDING'){
            return 'secondary';
        }elseif($this->status === 'COMPLETED'){
            return 'muted';
        }
    }
    public function night(){
        return $this->hasMany(ReservationNight::class,'reservation_id');
    }
    public function tax(){
        return $this->hasMany(ReservationTax::class,'reservation_id');
    }
    public function paidService(){
        return $this->hasMany(ReservationPaidService::class,'reservation_id');
    }
    public function food(){
        return $this->hasMany(ReservationFood::class,'reservation_id');
    }
    public function taxi(){
        return $this->hasMany(Taxi::class,'reservation_id');
    }
    // public function guest(){
    //     return $this->belongsTo(User::class,'user_id');
    // }

    public function guests(){
        // $guest_lists = Guest::where('reservation_id', $row->id)->pluck('name')->toArray();
        $guest_lists = $this->hasMany(Guest::class,'reservation_id')->pluck('name')->toArray();
        
        $guests = implode(", ",$guest_lists);
        return $guests;
    }

    public function appliedCoupon(){
        return $this->hasOne(AppliedCouponCode::class,'reservation_id');
    }
    public function logs(){
        return $this->hasMany(Logging::class,'reservation_id')->orderBy('created_at', 'desc');
    }
    public function discounts(){
        return $this->hasMany(Discount::class,'reservation_id');
    }
    public function payment(){
        return $this->hasMany(Payment::class,'reservetion_id')->where('status',1);
    }
    public function paymentStatus(){
        $payment = $this->payment->sum('amount');
        if($payment >0){
            if($payment < $this->payable()){
                return [
                    'color'=>'warning',
                    'status'=>'Partials'
                ];
            }else{
                return [
                    'color'=>'success',
                    'status'=>'Paid'
                ];
            }
        }else{
            return [
                'color'=>'danger',
                'status'=>'Due'
            ];
        }
    }
    public function totalPaidService(){
      return  $this->paidService->sum('price');
    }
    public function totalFood(){
    return  $this->food->sum('price');
    }
    public function totalNightPrice(){
      return  $this->night->sum('price');
    }
    public function totalTax(){
      return  $this->tax->sum('price');
    }
    public function totalTaxi(){
        return  $this->taxi->sum('price');
      }
    public function discount(){
        $discount = 0;

        if($coupon = $this->appliedCoupon){
            if($coupon->coupon_type === 'PERCENTAGE'){
                $night_price = $this->night->sum('price');
                $discount =  ($night_price*$coupon->coupon_rate)/100;
            }else{
                $discount =  $coupon->coupon_rate;
            }
        }
        return $discount;
    }

    public function total_discounts($sub_total){

        $total = 0;
        foreach($this->discounts AS $item){
            if($item->type === 'PERCENTAGE'){
                $discount = $sub_total*$item->value/100;
            }else{
                $discount = $item->value;
            }
            $total += $discount;
        }
        return $total;
    }

    public function only_discount(){
        $night = $this->totalNightPrice();
        $tax =$this->totalTax();
        $paid_service = $this->totalPaidService();
        $food = $this->totalFood();
        // $taxi = $this->totalTaxi();
        // $discounts = $this->discount();

        $total = $night+$tax+$paid_service+$food;

        $total_discounts = $this->total_discounts($total);
        
        return $total_discounts;
    }

    public function payable(){
        $night = $this->totalNightPrice();
        $tax =$this->totalTax();
        $paid_service = $this->totalPaidService();
        $food = $this->totalFood();
        // $taxi = $this->totalTaxi();
        // $discounts = $this->discount();

        $total = $night+$tax+$paid_service+$food;

        $total_discounts = $this->total_discounts($total);
        
        return ($total-$total_discounts);
    }
    public function codeableName(): string
    {
        return 'reservation';
    }
}
