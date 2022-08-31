<?php

namespace App\Http\Controllers\Backend\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Reservation;
use App\Model\RoomType;
use App\Model\ReservationFood;
use App\Model\Food;
use App\Model\ReservationPaidService;
use App\Model\ReservationNight;
use App\Model\PaidService;
use App\Model\Staff;
use App\Model\Payment;
use App\Model\Gateway;
use App\Model\Taxi;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function reservation()
    {
        $room_types = RoomType::select('id', 'title')->get();

        return view('backend.admin.reports.reservation',compact('room_types'));
    }

    public function reservation_list(Request $request)
    {
        $now = time();

        $starting = $request->starting;
        $ending = $request->ending;
        $month = $request->salesMonth;
        $room_type = $request->room_type;
        $booking_type = $request->booking_type;
        $booking_status = (string)$request->booking_status;

        $status = $request->status;
        // $checkin_start = $request->checkin_start;
        // $checkin_end = $request->checkin_end;
        // $checkout_start = $request->checkout_start;
        // $checkout_end = $request->checkout_end;

        if($request->ending==""||$request->ending=="0"||is_null($request->ending)){
            $request->ending = date("Y-m-d");
        }

        if($request->booking_type==""||$request->booking_type=="2"||is_null($request->booking_type)){
            $booking_type = 2;
        }

        if($request->starting==""||$request->starting=="0"||is_null($request->starting)){
            if($month=="0"||is_null($month)){

                $currentDay = date("Y-m-d", $now);
                $currentDayEpoch = strtotime($currentDay);

                $starting = strtotime("-1 month ", $currentDayEpoch);
                $starting = date('Y-m-d H:i:s', $starting);
                $ending = date('Y-m-d H:i:s', $now);

                $created_at = 'reservations.date BETWEEN "'.$starting.'" AND "'.$ending.'"';
            }
            else{
                $year = date("Y");
                if($month=="LNovember"||$month=="LDecember"||$month=="LOctober"){
                    $month = str_replace( "L" , "", $month );
                    $year = date("Y")-1;
                }

                $start = strtotime("1 ".$month." ".$year." 00:00:00");
                $starting = date('Y-m-d H:i:s', $start);

                $end = strtotime("+1 month -1 second", $start);
                $ending = date('Y-m-d H:i:s', $end);

                $created_at = 'reservations.date BETWEEN "'.$starting.'" AND "'.$ending.'"';
            }            
        }
        else{
            $starting = $request->starting." 00:00:00"; 
            $ending = $request->ending." 23:59:59";

            $created_at = 'reservations.date BETWEEN "'.$starting.'" AND "'.$ending.'"';
        }

        $room_type==0 ? $room_type_query = 'reservations.room_type_id IS NOT NULL' : $room_type_query = 'reservations.room_type_id = '.$room_type;
        $booking_type==2 ? $booking_type_query = 'reservations.online IS NOT NULL' : $booking_type_query = 'reservations.online = '.$booking_type;

        $booking_status=="0" ? $booking_status_query = 'reservations.status IS NOT NULL' : $booking_status_query = 'reservations.status = "'.$booking_status.'"';

        $reservations = Reservation::whereRaw($created_at)->whereRaw($room_type_query)->whereRaw($booking_status_query)->orderBy('date', 'desc')->get();

        $room_type=="0"  ? $room_type="All Room Types" : $room_type = RoomType::select('title')->where('id', $room_type)->first()->title;
        $booking_status=="0"  ? $booking_status="All Booking Status" : $booking_status = ucfirst(strtolower($booking_status));

        if($booking_type=="2") $booking_type="All";
        elseif($booking_type=="0") $booking_type="Offline";
        elseif($booking_type=="1") $booking_type="Online";

        return Datatables::of($reservations)
            ->addIndexColumn()
            ->addColumn('username', function($row){
                // $link = route('backend.admin.guests.view', $row->guest->id);
                // $guest = $row->guest->full_name;
                // $btn = '<a href="'.$link.'">'.$guest.'</a>';
                return $row->guests();
            })
            ->addColumn('room_type', function($row){
                $room_type = $row->roomType->title;
                return $room_type;
            })
            ->addColumn('online', function($row){
                $online = $row->online?'Online':'Offline';
                return $online;
            })
            ->addColumn('check_in', function($row){
                return date('Y-m-d H:i', strtotime($row->check_in));
            })
            ->addColumn('check_out', function($row){
                return date('Y-m-d H:i', strtotime($row->check_out));
            })
            ->addColumn('payment_status', function($row){
                $btn = "<span class='badge badge-".$row->paymentStatus()['color']."'>".$row->paymentStatus()['status']."</span>";
                return $btn;
            })
            ->addColumn('status', function($row){

                $status = $row->status === 'ONLINE_PENDING'?'PENDING':$row->status;

                $btn = "<span class='badge badge-".$row->statusClass()."'>".$status."</span>";
                return $btn;
            })
            ->addColumn('action', function($row){
                $link = route('backend.admin.reservation.view', $row->id);
                $btn = '<a href="'.$link.'" class="btn btn-tsk"><i class="fa fa-eye"></i> View</a>';
                return $btn;
            })
            ->rawColumns(['username', 'payment_status', 'status', 'action'])
            ->with('from', $starting)
            ->with('to', $ending)
            ->with('booking_status', $booking_status)
            ->with('room_type', $room_type)
            ->make(true);
    }

    public function food()
    {
        $foods = Food::select('id', 'title')->orderBy('title')->get();
        $staffs = Staff::orderBy('first_name')->get();

        return view('backend.admin.reports.food',compact('foods', 'staffs'));
    }

    public function food_list(Request $request)
    {
        $now = time();

        $starting = $request->starting;
        $ending = $request->ending;
        $month = $request->salesMonth;
        $food = $request->food;
        $staff = $request->staff;
        // $checkin_start = $request->checkin_start;
        // $checkin_end = $request->checkin_end;
        // $checkout_start = $request->checkout_start;
        // $checkout_end = $request->checkout_end;

        if($request->ending==""||$request->ending=="0"||is_null($request->ending)){
            $request->ending = date("Y-m-d");
        }

        if($request->food==""||$request->food=="0"||is_null($request->food)){
            $food = 0;
        }

        if($request->staff==""||$request->staff=="0"||is_null($request->staff)){
            $staff = 0;
        }

        if($request->starting==""||$request->starting=="0"||is_null($request->starting)){
            if($month=="0"||is_null($month)){

                $currentDay = date("Y-m-d", $now);
                $currentDayEpoch = strtotime($currentDay);

                $starting = strtotime("-1 month ", $currentDayEpoch);
                $starting = date('Y-m-d H:i:s', $starting);
                $ending = date('Y-m-d H:i:s', $now);

                $date = 'reservation_foods.date BETWEEN "'.$starting.'" AND "'.$ending.'"';
            }
            else{
                $year = date("Y");
                if($month=="LNovember"||$month=="LDecember"||$month=="LOctober"){
                    $month = str_replace( "L" , "", $month );
                    $year = date("Y")-1;
                }

                $start = strtotime("1 ".$month." ".$year." 00:00:00");
                $starting = date('Y-m-d H:i:s', $start);

                $end = strtotime("+1 month -1 second", $start);
                $ending = date('Y-m-d H:i:s', $end);

                $date = 'reservation_foods.date BETWEEN "'.$starting.'" AND "'.$ending.'"';
            }            
        }
        else{
            $starting = $request->starting." 00:00:00"; 
            $ending = $request->ending." 23:59:59";

            $date = 'reservation_foods.date BETWEEN "'.$starting.'" AND "'.$ending.'"';
        }

        $food==0 ? $food_query = 'reservation_foods.food_id IS NOT NULL' : $food_query = 'reservation_foods.food_id = '.$food;
        $staff==0 ? $staff_query = 'reservation_foods.staff_id IS NOT NULL' : $staff_query = 'reservation_foods.staff_id = '.$staff;

        $reservations = ReservationFood::whereRaw($date)->whereRaw($food_query)->whereRaw($staff_query)->orderBy('reservation_foods.date', 'desc')->get();

        $total = ReservationFood::whereRaw($date)->whereRaw($food_query)->whereRaw($staff_query)->sum('price');

        $food=="0"  ? $food= "All Food & Beverages" : $food = Food::select('title')->where('id', $food)->first()->title; 
        $staff=="0"  ? $staff="All Staffs" : $staff = Staff::where('id', $staff)->first()->full_name;

        return Datatables::of($reservations)
            ->addIndexColumn()
            ->addColumn('username', function($row){
                // $link = route('backend.admin.guests.view', $row->guest->id);
                // $guest = $row->guest->full_name;
                // $btn = '<a href="'.$link.'">'.$guest.'</a>';
                return $row->reservation->guests();
            })
            ->addColumn('uid', function($row){
                $uid = $row->reservation->uid;
                return $uid;
            })
            ->addColumn('room', function($row){
                $time = $row->date;

                $reservation_night = ReservationNight::where('reservation_id', $row->reservation->id)->where('check_in', '<=', $time)->where('check_out', '>', $time)->first();

                $room = $row->reservation->id;
                return $room;
            })
            ->addColumn('value', function($row){
                $value = number_format($row->value, 2, ".", ",");
                return $value;
            })
            ->addColumn('date', function($row){
                $value = date("Y-m-d H:i", strtotime($row->date));
                return $value;
            })
            ->addColumn('price', function($row){
                $price = number_format($row->price, 2, ".", ",");
                return $price;
            })
            ->addColumn('food_name', function($row){
                $food_name = $row->food->title;
                return $food_name;
            })
            ->addColumn('staff_name', function($row){
                $staff_name = $row->staff->fullname;
                return $staff_name;
            })
            ->addColumn('action', function($row){
                $link = route('backend.admin.reservation.view', $row->reservation->id);
                $btn = '<a href="'.$link.'" class="btn btn-tsk"><i class="fa fa-eye"></i> View</a>';
                return $btn;
            })
            ->rawColumns(['username', 'action'])
            ->with('from', $starting)
            ->with('to', $ending)
            ->with('food', $food)
            ->with('staff', $staff)
            ->with('total', number_format($total, 2, ".", ","))
            ->make(true);
    }

    public function daily()
    {
        return view('backend.admin.reports.daily');
    }

    public function daily_list(Request $request)
    {
        $starting = $request->starting." 00:00:00"; 
        $ending = $request->starting." 23:59:59";

        $reservations = Reservation::whereHas('night', function ($query) use ($starting, $ending) {
            $query->whereBetween('check_in', [ $starting, $ending ]);
            $query->where('reservations.status', '!=', ' CANCEL');
        })
        ->orWhereHas('paidService', function ($query) use ($starting, $ending) {
            $query->whereBetween('date', [ $starting, $ending ]);
        })
        ->orWhereHas('food', function ($query) use ($starting, $ending) {
            $query->whereBetween('date', [ $starting, $ending ]);
        })->get();
        
        
        // whereRaw($date)->whereRaw($food_query)->whereRaw($staff_query)->orderBy('reservation_foods.date', 'desc')->get();

        $room_total = ReservationNight::whereBetween('check_in', [ $starting, $ending ])->sum('price');

        $food_total = ReservationFood::whereBetween('date', [ $starting, $ending ])->sum('price');

        $service_total = ReservationPaidService::whereBetween('date', [ $starting, $ending ])->sum('price');

        $total = $room_total + $food_total + $service_total;

        return Datatables::of($reservations)
            ->addIndexColumn()
            ->addColumn('username', function($row){
                // $link = route('backend.admin.guests.view', $row->guest->id);
                // $guest = $row->guest->full_name;
                // $btn = '<a href="'.$link.'">'.$guest.'</a>';
                return $row->guests();
            })
            ->addColumn('uid', function($row){
                $uid = $row->uid;
                return $uid;
            })
            ->addColumn('room', function($row) use ($starting, $ending){
                $room = ReservationNight::where('reservation_id', $row->id)->whereBetween('check_in', [ $starting, $ending ])->first();
                // return $$room->price;
                return number_format($room['price'], 2, ".", ",");
            })
            ->addColumn('food', function($row) use ($starting, $ending){
                $room = ReservationFood::where('reservation_id', $row->id)->whereBetween('date', [ $starting, $ending ])->first();
                // return $$room->price;
                return number_format($room['price'], 2, ".", ",");
            })
            ->addColumn('service', function($row) use ($starting, $ending){
                $room = ReservationPaidService::where('reservation_id', $row->id)->whereBetween('date', [ $starting, $ending ])->first();
                // return $$room->price;
                return number_format($room['price'], 2, ".", ",");
            })
            ->addColumn('action', function($row){
                $link = route('backend.admin.reservation.view', $row->id);
                $btn = '<a href="'.$link.'" class="btn btn-tsk"><i class="fa fa-eye"></i> View</a>';
                return $btn;
            })
            ->rawColumns(['username', 'action'])
            ->with('from', $starting)
            ->with('to', $ending)
            ->with('room_total', number_format($room_total, 2, ".", ","))
            ->with('food_total', number_format($food_total, 2, ".", ","))
            ->with('service_total', number_format($service_total, 2, ".", ","))
            ->with('total', number_format($total, 2, ".", ","))
            ->make(true);
    }

    public function service()
    {
        $services = PaidService::select('id', 'title')->orderBy('title')->get();
        $staffs = Staff::orderBy('first_name')->get();

        return view('backend.admin.reports.service',compact('services', 'staffs'));
    }

    public function service_list(Request $request)
    {
        $now = time();

        $starting = $request->starting;
        $ending = $request->ending;
        $month = $request->salesMonth;
        $service = $request->service;
        $staff = $request->staff;
        // $checkin_start = $request->checkin_start;
        // $checkin_end = $request->checkin_end;
        // $checkout_start = $request->checkout_start;
        // $checkout_end = $request->checkout_end;

        if($request->ending==""||$request->ending=="0"||is_null($request->ending)){
            $request->ending = date("Y-m-d");
        }

        if($request->service==""||$request->service=="0"||is_null($request->service)){
            $service = 0;
        }

        if($request->staff==""||$request->staff=="0"||is_null($request->staff)){
            $staff = 0;
        }

        if($request->starting==""||$request->starting=="0"||is_null($request->starting)){
            if($month=="0"||is_null($month)){

                $currentDay = date("Y-m-d", $now);
                $currentDayEpoch = strtotime($currentDay);

                $starting = strtotime("-1 month ", $currentDayEpoch);
                $starting = date('Y-m-d H:i:s', $starting);
                $ending = date('Y-m-d H:i:s', $now);

                $date = 'reservation_paid_services.date BETWEEN "'.$starting.'" AND "'.$ending.'"';
            }
            else{
                $year = date("Y");
                if($month=="LNovember"||$month=="LDecember"||$month=="LOctober"){
                    $month = str_replace( "L" , "", $month );
                    $year = date("Y")-1;
                }

                $start = strtotime("1 ".$month." ".$year." 00:00:00");
                $starting = date('Y-m-d H:i:s', $start);

                $end = strtotime("+1 month -1 second", $start);
                $ending = date('Y-m-d H:i:s', $end);

                $date = 'reservation_paid_services.date BETWEEN "'.$starting.'" AND "'.$ending.'"';
            }            
        }
        else{
            $starting = $request->starting." 00:00:00"; 
            $ending = $request->ending." 23:59:59";

            $date = 'reservation_paid_services.date BETWEEN "'.$starting.'" AND "'.$ending.'"';
        }

        $service==0 ? $service_query = 'reservation_paid_services.pad_service_id IS NOT NULL' : $service_query = 'reservation_paid_services.pad_service_id = '.$service;
        $staff==0 ? $staff_query = 'reservation_paid_services.staff_id IS NOT NULL' : $staff_query = 'reservation_paid_services.staff_id = '.$staff;

        $reservations = ReservationPaidService::whereRaw($date)->whereRaw($service_query)->whereRaw($staff_query)->orderBy('reservation_paid_services.date', 'desc')->get();

        $total = ReservationPaidService::whereRaw($date)->whereRaw($service_query)->whereRaw($staff_query)->sum('price');

        $service=="0"  ? $service= "All Paid Services" : $service = PaidService::select('title')->where('id', $service)->first()->title; 
        $staff=="0"  ? $staff="All Staffs" : $staff = Staff::where('id', $staff)->first()->full_name;

        return Datatables::of($reservations)
            ->addIndexColumn()
            ->addColumn('username', function($row){
                // $link = route('backend.admin.guests.view', $row->guest->id);
                // $guest = $row->guest->full_name;
                // $btn = '<a href="'.$link.'">'.$guest.'</a>';
                return $row->reservation->guests();
            })
            ->addColumn('uid', function($row){
                $uid = $row->reservation->uid;
                return $uid;
            })
            ->addColumn('value', function($row){
                $value = number_format($row->value, 2, ".", ",");
                return $value;
            })
            ->addColumn('date', function($row){
                $value = date("Y-m-d H:i", strtotime($row->date));
                return $value;
            })
            ->addColumn('price', function($row){
                $price = number_format($row->price, 2, ".", ",");
                return $price;
            })
            ->addColumn('service_name', function($row){
                $service_name = $row->service->title;
                return $service_name;
            })
            ->addColumn('staff_name', function($row){
                $staff_name = $row->staff->full_name;
                return $staff_name;
            })
            ->addColumn('action', function($row){
                $link = route('backend.admin.reservation.view', $row->reservation->id);
                $btn = '<a href="'.$link.'" class="btn btn-tsk"><i class="fa fa-eye"></i> View</a>';
                return $btn;
            })
            ->rawColumns(['username', 'service_name', 'action'])
            ->with('from', $starting)
            ->with('to', $ending)
            ->with('service', $service)
            ->with('staff', $staff)
            ->with('total', number_format($total, 2, ".", ","))
            ->make(true);
    }

    public function taxi()
    {
        return view('backend.admin.reports.taxi');
    }

    public function taxi_list(Request $request)
    { 
        $now = time();

        $starting = $request->starting;
        $ending = $request->ending;
        $month = $request->salesMonth;

        if($request->ending==""||$request->ending=="0"||is_null($request->ending)){
            $request->ending = date("Y-m-d");
        }

        if($request->starting==""||$request->starting=="0"||is_null($request->starting)){
            if($month=="0"||is_null($month)){

                $currentDay = date("Y-m-d", $now);
                $currentDayEpoch = strtotime($currentDay);

                $starting = strtotime("-1 month ", $currentDayEpoch);
                $starting = date('Y-m-d H:i:s', $starting);
                $ending = date('Y-m-d H:i:s', $now);

                $date = 'taxis.created_at BETWEEN "'.$starting.'" AND "'.$ending.'"';
            }
            else{
                $year = date("Y");
                if($month=="LNovember"||$month=="LDecember"||$month=="LOctober"){
                    $month = str_replace( "L" , "", $month );
                    $year = date("Y")-1;
                }

                $start = strtotime("1 ".$month." ".$year." 00:00:00");
                $starting = date('Y-m-d H:i:s', $start);

                $end = strtotime("+1 month -1 second", $start);
                $ending = date('Y-m-d H:i:s', $end);

                $date = 'taxis.created_at BETWEEN "'.$starting.'" AND "'.$ending.'"';
            }            
        }
        else{
            $starting = $request->starting." 00:00:00"; 
            $ending = $request->ending." 23:59:59";

            $date = 'taxis.created_at BETWEEN "'.$starting.'" AND "'.$ending.'"';
        }

        $reservations = Taxi::whereRaw($date)->orderBy('taxis.created_at', 'desc')->get();

        $total = Taxi::whereRaw($date)->sum('price');

        return Datatables::of($reservations)
            ->addIndexColumn()
            ->addColumn('username', function($row){
                // $link = route('backend.admin.guests.view', $row->guest->id);
                // $guest = $row->guest->full_name;
                // $btn = '<a href="'.$link.'">'.$guest.'</a>';
                return $row->reservation->guests();
            })
            ->addColumn('uid', function($row){
                $uid = $row->reservation->uid;
                return $uid;
            })
            ->addColumn('date', function($row){
                $value = date("Y-m-d H:i", strtotime($row->created_at));
                return $value;
            })
            ->addColumn('price', function($row){
                $price = number_format($row->price, 2, ".", ",");
                return $price;
            })
            ->addColumn('action', function($row){
                $link = route('backend.admin.reservation.view', $row->reservation->id);
                $btn = '<a href="'.$link.'" class="btn btn-tsk"><i class="fa fa-eye"></i> View</a>';
                return $btn;
            })
            ->rawColumns(['username', 'action'])
            ->with('from', $starting)
            ->with('to', $ending)
            ->with('total', number_format($total, 2, ".", ","))
            ->make(true);
    }

    public function sale()
    {
        return view('backend.admin.reports.sale');
    }

    public function sale_list(Request $request)
    {
        $now = time();

        $starting = $request->starting;
        $ending = $request->ending;
        $month = $request->salesMonth;

        if($request->ending==""||$request->ending=="0"||is_null($request->ending)){
            $request->ending = date("Y-m-d");
        }

        if($request->starting==""||$request->starting=="0"||is_null($request->starting)){
            if($month=="0"||is_null($month)){

                $currentDay = date("Y-m-d", $now);
                $currentDayEpoch = strtotime($currentDay);

                $starting = strtotime("-1 month ", $currentDayEpoch);
                $starting = date('Y-m-d H:i:s', $starting);
                $ending = date('Y-m-d H:i:s', $now);

                $date = 'reservations.date BETWEEN "'.$starting.'" AND "'.$ending.'"';
            }
            else{
                $year = date("Y");
                if($month=="LNovember"||$month=="LDecember"||$month=="LOctober"){
                    $month = str_replace( "L" , "", $month );
                    $year = date("Y")-1;
                }

                $start = strtotime("1 ".$month." ".$year." 00:00:00");
                $starting = date('Y-m-d H:i:s', $start);

                $end = strtotime("+1 month -1 second", $start);
                $ending = date('Y-m-d H:i:s', $end);

                $date = 'reservations.date BETWEEN "'.$starting.'" AND "'.$ending.'"';
            }            
        }
        else{
            $starting = $request->starting." 00:00:00"; 
            $ending = $request->ending." 23:59:59";

            $date = 'reservations.date BETWEEN "'.$starting.'" AND "'.$ending.'"';
        }

        $reservations = Reservation::whereRaw($date)->orderBy('reservations.date', 'desc')->where('reservations.status', '!=', ' CANCEL')->get();

        $total = 1000;

        $room_total = Reservation::join('reservation_nights', 'reservations.id', '=', 'reservation_nights.reservation_id')
            ->where('reservations.status', '!=', ' CANCEL')->whereRaw($date)->sum('price');

        $food_total = Reservation::join('reservation_foods', 'reservations.id', '=', 'reservation_foods.reservation_id')
            ->where('reservations.status', '!=', ' CANCEL')->whereRaw($date)->sum('price');

        $service_total = Reservation::join('reservation_paid_services', 'reservations.id', '=', 'reservation_paid_services.reservation_id')
            ->where('reservations.status', '!=', ' CANCEL')->whereRaw($date)->sum('price');

        $discount = 0;

        foreach($reservations AS $reservation){
            $discount += $reservation->only_discount();
        }

        $total = $room_total + $food_total +  $service_total - $discount;

        return Datatables::of($reservations)
            ->addIndexColumn()
            ->addColumn('username', function($row){
                // $link = route('backend.admin.guests.view', $row->guest->id);
                // $guest = $row->guest->full_name;
                // $btn = '<a href="'.$link.'">'.$guest.'</a>';
                return $row->guests();
            })
            ->addColumn('uid', function($row){
                $uid = $row->uid;
                return $uid;
            })
            ->addColumn('date', function($row){
                $value = date("Y-m-d H:i", strtotime($row->date));
                return $value;
            })
            ->addColumn('room_charges', function($row){
                $price = number_format($row->totalNightPrice(), 2, ".", ",");
                return $price;
            })
            ->addColumn('food_charges', function($row){
                $price = number_format($row->totalFood(), 2, ".", ",");
                return $price;
            })
            ->addColumn('service_charges', function($row){
                $price = number_format($row->totalPaidService(), 2, ".", ",");
                return $price;
            })
            ->addColumn('discount', function($row){

                $discount = $row->only_discount();

                $discount!=0 ? $price = "(".number_format($row->only_discount(), 2, ".", ",").")" : $price = number_format($row->only_discount(), 2, ".", ",");
                
                return $price;
            })
            ->addColumn('total', function($row){
                $price = number_format($row->payable(), 2, ".", ",");
                return $price;
            })
            ->addColumn('action', function($row){
                $link = route('backend.admin.reservation.view', $row->id);
                $btn = '<a href="'.$link.'" class="btn btn-tsk"><i class="fa fa-eye"></i> View</a>';
                return $btn;
            })
            ->rawColumns(['username', 'action'])
            ->with('from', $starting)
            ->with('to', $ending)
            // ->with('food', $food)
            // ->with('staff', $staff)
            ->with('room_total', number_format($room_total, 2, ".", ","))
            ->with('food_total', number_format($food_total, 2, ".", ","))
            ->with('service_total', number_format($service_total, 2, ".", ","))
            ->with('discount_total', number_format($discount, 2, ".", ","))
            ->with('total', number_format($total, 2, ".", ","))
            ->make(true);
    }

    public function payment()
    {
        $methods = Gateway::select('id', 'name')->where('status',1)->where('is_offline',1)->get();

        return view('backend.admin.reports.payment', compact('methods'));
    }

    public function payment_list(Request $request)
    {
        $now = time();

        $starting = $request->starting;
        $ending = $request->ending;
        $month = $request->salesMonth;
        $method = $request->method;

        if($request->ending==""||$request->ending=="0"||is_null($request->ending)){
            $request->ending = date("Y-m-d");
        }

        if($request->method==""||$request->method=="0"||is_null($request->method)){
            $method = 0;
        }

        if($request->starting==""||$request->starting=="0"||is_null($request->starting)){
            if($month=="0"||is_null($month)){

                $currentDay = date("Y-m-d", $now);
                $currentDayEpoch = strtotime($currentDay);

                $starting = strtotime("-1 month ", $currentDayEpoch);
                $starting = date('Y-m-d H:i:s', $starting);
                $ending = date('Y-m-d H:i:s', $now);

                $created_at = 'payments.created_at BETWEEN "'.$starting.'" AND "'.$ending.'"';
            }
            else{
                $year = date("Y");
                if($month=="LNovember"||$month=="LDecember"||$month=="LOctober"){
                    $month = str_replace( "L" , "", $month );
                    $year = date("Y")-1;
                }

                $start = strtotime("1 ".$month." ".$year." 00:00:00");
                $starting = date('Y-m-d H:i:s', $start);

                $end = strtotime("+1 month -1 second", $start);
                $ending = date('Y-m-d H:i:s', $end);

                $created_at = 'payments.created_at BETWEEN "'.$starting.'" AND "'.$ending.'"';
            }            
        }
        else{
            $starting = $request->starting." 00:00:00"; 
            $ending = $request->ending." 23:59:59";

            $created_at = 'payments.created_at BETWEEN "'.$starting.'" AND "'.$ending.'"';
        }

        $method==0 ? $method_query = 'payments.gateway_id IS NOT NULL' : $method_query = 'payments.gateway_id = '.$method;

        $reservations = Payment::whereRaw($created_at)->whereRaw($method_query)->orderBy('payments.created_at', 'desc')->get();

        $method==0 ? $method = "All Payment Methods" : $method = Gateway::select('name')->where('id', $method)->first()->name;
        $total = Payment::whereRaw($created_at)->sum('amount');

        return Datatables::of($reservations)
            ->addIndexColumn()
            ->addColumn('username', function($row){
                // $link = route('backend.admin.guests.view', $row->guest->id);
                // $guest = $row->guest->full_name;
                // $btn = '<a href="'.$link.'">'.$guest.'</a>';
                return $row->reservation->guests();
            })
            ->addColumn('uid', function($row){
                $uid = $row->reservation->uid;
                return $uid;
            })
            ->addColumn('date', function($row){
                $date = $row->created_at;
                return $date;
            })
            ->addColumn('amount', function($row){
                $amount = number_format($row->amount, 2,".", ",");
                return $amount;
            })
            ->addColumn('method', function($row){
                $method = $row->gateway->name;
                return $method;
            })
            ->addColumn('payment_status', function($row){
                $btn = "<span class='badge badge-".$row->reservation->paymentStatus()['color']."'>".$row->reservation->paymentStatus()['status']."</span>";
                return $btn;
            })
            ->addColumn('action', function($row){
                $link = route('backend.admin.reservation.view', $row->reservation->id);
                $btn = '<a href="'.$link.'" class="btn btn-tsk"><i class="fa fa-eye"></i> View</a>';
                return $btn;
            })
            ->rawColumns(['username', 'payment_status','action'])
            ->with('from', $starting)
            ->with('to', $ending)
            ->with('method', $method)
            // ->with('staff', $staff)
            ->with('total', number_format($total, 2, ".", ","))
            ->make(true);
    }


}
