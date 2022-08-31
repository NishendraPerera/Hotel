<?php

namespace App\Http\Controllers\Backend\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Room;
use App\Model\RoomType;
use App\Model\ReservationNight;
use App\Model\Reservation;
use App\Model\Guest;
use App\Model\Taxi;
use App\Model\Food;
use App\Model\PaidService;
use App\Model\Discount;
use DB;
use Carbon\Carbon;

use App\Repositories\LogFunctions;

class ReservationNewController extends Controller
{
    use LogFunctions;

    public function get_room_type_details(Request $request)
    {
        $room_type_id = $request->room_type_id;

        $room_type = RoomType::select('hours', 'base_price', 'higher_capacity', 'kids_capacity')->where('id', $room_type_id)->first();

        return $room_type;
    }

    public function get_available_dates(Request $request)
    {
        $check_in_date = $request->check_in_date;
        $room_type_id = $request->room_type_id;
        $room_number_array_primary = [];
        $total = 0;

        $hours = RoomType::select('hours')->where('id', $room_type_id)->first()->hours;
        $hours==24 ? $check_out_date = $request->check_out_date : $check_out_date = date('Y-m-d H:i:s', strtotime("+".$hours." hours", strtotime($check_in_date)));

        $rooms = Room::select('id', 'number')->where('room_type_id', $room_type_id)->get();

        foreach($rooms as $key => $room){ if(!in_array($room->number, $room_number_array_primary)) $room_number_array_primary[] = $room->number; }

        $room_type = RoomType::findOrFail($room_type_id);

        $room_number_array_secondary = $room_number_array_primary;
        foreach($room_number_array_secondary as $key => $room){

            if(in_array($room, $room_number_array_primary)){
                $nights = ReservationNight::join('reservations', 'reservation_nights.reservation_id', '=', 'reservations.id')
                ->join('rooms', 'reservation_nights.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('reservation_nights.check_out', '>', $check_in_date)->where('reservation_nights.check_in', '<', $check_out_date)
                ->where('reservations.status', "!=", "CANCEL")
                ->where('reservations.status', "!=", "COMPLETED")
                ->where('rooms.number', $room)->get();
            }
            if(count($nights)>0) unset($room_number_array_primary[$key]);
        }

        sort($room_number_array_primary);

        if(count($room_number_array_primary)>0){
            if($hours==24){
                $datediff = strtotime(date('Y-m-d', strtotime($check_out_date))) - strtotime(date('Y-m-d', strtotime($check_in_date)));
                $days = round($datediff / (60 * 60 * 24));
    
                for($i=0; $i<$days; $i++){
                    $from = date('Y-m-d', strtotime("+".$i." days", strtotime($check_in_date)));
                    $price = $this->getPrice($from, $room_type);
                    $price_text = "Rs. ".number_format($price, 0, ".", ",");
                    $total += $price;
                    $result[] = [
                        "from" => $from,
                        "to" => date('Y-m-d', strtotime("+".($i+1)." days", strtotime($check_in_date))),
                        "rooms" => $room_number_array_primary,
                        "price" => $price_text
                    ];
                }
            }
            else{
                $from = date('Y-m-d H:i', strtotime($check_in_date));
                $price = $this->getPrice($from, $room_type);
                    $price_text = "Rs. ".number_format($price, 0, ".", ",");
                    $total += $price;
                $result[] = [
                    "from" => date('Y-m-d H:i', strtotime($check_in_date)),
                    "to" => date('Y-m-d H:i', strtotime($check_out_date)),
                    "rooms" => $room_number_array_primary,
                    "price" => $price_text
                ];
            }
        }
        else
            $result = "no_rooms";
        
        $total = "Rs. ".number_format($total, 0, ".", ",");
        
        return [$result, $total];
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            // 'guest_id'=>'required|integer',
            'guests'=>'required|array',
            'room_type_id'=>'required|integer',
            'adults'=>'required|integer|min:1',
            'kids'=>'required|integer|min:0',
            'check_in'=>'required|date|after_or_equal:today',
            // 'check_out'=>'required|date|after_or_equal:check_in',
            'room_list' => 'required|array'
        ]);

        $hours = RoomType::select('hours')->where('id', $request->room_type_id)->first()->hours;
        $hours==24 ? $check_out_date = $request->check_out_date." ".date('H:i:s', strtotime($request->check_in)) : $check_out_date = date('Y-m-d H:i:s', strtotime("+".$hours." hours", strtotime($request->check_in)));

        DB::beginTransaction();

        try{
            $reservation = new Reservation;
            $reservation->uid = rand(1111,9999).'-'.time();
        	$reservation->date = date("Y-m-d H:i:s");
            $reservation->user_id = $request->guest_id;
            $reservation->room_type_id = $request->room_type_id;
            $reservation->adults = $request->adults;
            $reservation->kids = $request->kids;
            $reservation->check_in = $request->check_in;
            $reservation->check_out = $check_out_date;
            $reservation->number_of_room = 1;
            $reservation->status = 'SUCCESS';
            $reservation->save();

            foreach($request->guests AS $item){

                if($item['name']!=""&&!is_null($item['name'])&&$item['name']!=" "){
                    $guest = new Guest;
                    $guest->name = $item['name'];
                    $guest->id_no = $item['id'];
                    $guest->phone = $item['phone'];
                    $guest->address = $item['address'];
                    $guest->reservation_id = $reservation->id;
                    $guest->save();
                }                
            }

            $price = RoomType::select('base_price as price')->where('id', $request->room_type_id)->first()->price;

            foreach($request->room_list AS $key => $room)
            {
                $room_id = Room::select('id')->where('room_type_id', $request->room_type_id)->where('number', $room)->first()->id;

                $check_in   = date('Y-m-d H:i:s', strtotime("+".$key." days", strtotime($request->check_in)));
                $hours==24 ? $check_out = date('Y-m-d H:i:s', strtotime("+".($key+1)." days", strtotime($request->check_in))) 
                        : $check_out = date('Y-m-d H:i:s', strtotime("+".$hours." hours", strtotime($request->check_in))); 

                $night = new ReservationNight;
                $night->reservation_id = $reservation->id;
                $night->date = date('Y-m-d', strtotime($check_in));
                $night->check_in = $check_in;
                $night->check_out = $check_out;
                $night->price = $price;
                $night->room_id = $room_id;
                $night->save();
            }

            // foreach ($request->tax as $v){
            //     $tax = new $this->reservationTax;
            //     $tax->reservation_id = $reservation->id;
            //     $tax->tax_id = $v['id'];
            //     $tax->type = $v['type'];
            //     $tax->value = $v['rate'];
            //     $tax->price = $v['value'];
            //     $tax->save();
            // }

            $log_info = "New reservation added";
            $this->logging($reservation->id, $log_info);

        DB::commit();

        $request->session()->flash('success','Reservation created successfully!');

        return "success";

        }
        catch (\Exception $e){
            $status = false;
            return [$e->getMessage(), $e->getLine()];
            DB::rollback();
        }

    }

    protected function getPrice($date,RoomType $room_type)
    {
        $day = Carbon::parse($date)->dayOfWeek+1;
        return $room_type->getDayByCurrentPrice($day);
    }

    public function room_type_change(Request $request, $id)
    {
        DB::beginTransaction();

        try{
            $new_room_type_id = $request->new_room_type;

            $last_night = ReservationNight::where('reservation_id', $id)->orderBy('id', 'desc')->first();
            $last_room_number = Room::where('id', $last_night->room_id)->first()->number;
            $new_room = Room::where('number', $last_room_number)->where('room_type_id', $new_room_type_id)->firstOrFail();
            $new_room_type = RoomType::find($new_room_type_id);

            $new_last_night = ReservationNight::find($last_night->id);
            $new_last_night->room_id = $new_room->id;
            $new_last_night->check_out = date('Y-m-d H:i:s', strtotime("+".$new_room_type->hours." hours", strtotime($new_last_night->check_in))); 
            $new_last_night->price = $new_room_type->getDayByCurrentPrice(Carbon::parse($new_last_night->check_out)->dayOfWeek+1);
            $new_last_night->save();

            $old_room_type = Reservation::findOrFail($id)->roomType->title;

            Reservation::where('id', $id)->update(['room_type_id' => $new_room_type_id]);

            $log_info = "Room type changed. From: \"".$old_room_type."\", To: \"".$new_room_type->title."\"";
            $this->logging($id, $log_info);

            DB::commit();

            return back()->with('success','Room type changed');
        }
        catch (\Exception $e){
            $status = false;
            return [$e->getMessage(), $e->getLine()];
            DB::rollback();
        }
    }

    public function taxi(Request $request, $id)
    {
        $taxi = new Taxi;
        $taxi->reservation_id = $id;
        $taxi->price = $request->price;
        $taxi->save();

        $log_info = "Taxi added for Rs. $request->price";
        $this->logging($id, $log_info);

        return back()->with('success','Taxi added');
    }

    public function manual_checkout(Request $request, $id)
    {
        $reservation = Reservation::find($id); $old_check_out_time = $reservation->check_out;
        $reservation->check_out = $request->checkout_time;
        $reservation->save();

        $log_info = "Check out time changed. From: $old_check_out_time, To: $reservation->check_out";
        $this->logging($id, $log_info);

        return back()->with('success','Check out time added');
    }

    public function discount(Request $request, $id)
    {
        $discount = new Discount;
        $discount->reservation_id = $id;
        $discount->type = $request->discount_type;
        $discount->value = $request->discount_value;
        $discount->save();

        $log_info = "Discount added. Type: $request->discount_type, Value: $request->discount_value";
        $this->logging($id, $log_info);

        return back()->with('success','Discount added');
    }

    public function removeTaxi($id){
        $taxi = Taxi::findOrFail($id);

        $log_info = "Taxi removed";
        $this->logging($taxi->reservation_id, $log_info);

        $taxi->delete();

        return back()->with('success','Taxi Delete Successful');
    }

    public function removeDiscount($id){
        $discount = Discount::findOrFail($id);

        $log_info = "Discount Removed. Type: $discount->type, Value: $discount->value";
        $this->logging($discount->reservation_id, $log_info);

        $discount->delete();

        return back()->with('success','Discount Delete Successful');
    }

    public function food_select(Request $request)
    {
        $search = $request->searchTerm;

        if($search==''){ 
            $data = Food::select('id', 'title as text')->limit(5)->get();
        }else{
            $data = Food::select('id', 'title as text')->where('title', 'like', '%' . $search . '%')->limit(5)->get();
        }

        return response()->json(["items" => $data]);
    }

    public function service_select(Request $request)
    {
        $search = $request->searchTerm;

        if($search==''){ 
            $data = PaidService::select('id', 'title as text')->limit(5)->get();
        }else{
            $data = PaidService::select('id', 'title as text')->where('title', 'like', '%' . $search . '%')->limit(5)->get();
        }

        return response()->json(["items" => $data]);
    }

    // returns true if doesn't overlap
    static function overlap($start_time1,$end_time1,$start_time2, $end_time2)
    {
        //$utc = new DateTimeZone('Asia/Colombo');

        $start1 = strtotime($start_time1);
        $end1 = strtotime($end_time1);
        if($end1 < $start1){
            //throw new Exception('Range is negative.');

            throw new Exception(1);
        }
        $start2 = strtotime($start_time2);
        $end2 = strtotime($end_time2);
        if($end2 < $start2){
            //throw new Exception('Range is negative.');

            throw new Exception(2);
        }

        return ($end1 <= $start2) || ($end2 <= $start1);
    }

    // returns true if overlaps
    static function coincide($start_time1,$end_time1,$start_time2, $end_time2)
    {
        //$utc = new DateTimeZone('Asia/Colombo');

        $start1 = strtotime($start_time1);
        $end1 = strtotime($end_time1);
        if($end1 < $start1){
            //throw new Exception('Range is negative.');

            throw new Exception("End: ".date('Y-m-d H:i:s', $end1).", Start: ".date('Y-m-d H:i:s', $start1));
        }
        $start2 = strtotime($start_time2);
        $end2 = strtotime($end_time2);
        if($end2 < $start2){
            //throw new Exception('Range is negative.');

            throw new Exception("End: ".date('Y-m-d H:i:s', $end2).", Start: ".date('Y-m-d H:i:s', $start2));
        }

        return ($end1 <= $start2) || ($end2 <= $start1);
    }
}
