@extends('backend.master')
@section('title',"Reservation")
@section('content')
    <div class="card">
        <div class="card-header bg-white d-print-none">
            <h2>Reservation

                <div class="float-right ml-2">

                    <a class="btn btn-tsk " href="{{route('backend.admin.reservation')}}"><i class="fa fa-list"></i> Reservation List</a>

                    @if(Auth::user()->role==0||$reservation->status!='COMPLETED')
                        <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#add_service"><i class="fa fa-bell"></i> Add Service</a>
                        <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#add_food"><i class="fa fa-cutlery"></i> Add Food & Beverage</a>

                        @if($reservation->payable()-$reservation->payment->sum('amount')!=0)
                            <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#add_payment"><i class="fa fa-money"></i> Add Payment</a>
                        @endif

                        @if(\App\Model\Taxi::where('reservation_id', $reservation->id)->count()<1)
                            <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#add_taxi"><i class="fa fa-taxi"></i> Add Taxi</a>
                        @endif

                        @if(Auth::user()->role==0&&\App\Model\Discount::where('reservation_id', $reservation->id)->count()<1)
                            <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#add_discount"><i class="fa fa-arrow-circle-down"></i> Add Discount</a>
                        @endif
                    @endif
                    
                    <a class="btn btn-tsk" onclick="javascript:window.print()"><i class="fa fa-print"></i></a>
                </div>
                @if(Auth::user()->role==0||$reservation->status!='COMPLETED')
                    @if($reservation->status === 'ONLINE_PENDING')
                        <div class="float-right mr-2">
                            <a class="btn btn-secondary " href="{{route('backend.admin.reservation.confirm',$reservation->id)}}"> Make Confirm</a>
                        </div>
                        @else
                        <div class="dropdown float-right mr-2">
                            <button type="button" class="btn btn-{{$reservation->statusClass()}} dropdown-toggle" data-toggle="dropdown">
                                {{ucfirst($reservation->status)}}
                            </button>
                            <div class="dropdown-menu">
                                @foreach(['SUCCESS','PENDING','CANCEL', 'COMPLETED'] as $status)
                                    @if($status !== $reservation->status)
                                <a class="dropdown-item" href="{{route('backend.admin.reservation.change_status',[$reservation->id,strtolower($status)])}}">{{ucfirst($status)}}</a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </h2>

        </div>
        <div class="card-body">
            <ul class="nav nav-tabs d-print-none" role="tablist">
                <li class="nav-item">
                    <a class="nav-link  active" href="#Details" role="tab" data-toggle="tab" aria-selected="true">Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#Payments" role="tab" data-toggle="tab">Payments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#Room" role="tab" data-toggle="tab">Room</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#Service" role="tab" data-toggle="tab">Service</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#Food" role="tab" data-toggle="tab">Food & Beverage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#Logs" role="tab" data-toggle="tab">Logs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#print" role="tab" data-toggle="tab">Print View</a>
                </li>

            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="Details">
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <h2 class="page-header">
                                <img src="{{general_setting()->logo}}" style="max-height: 100px" /></i> <small class="pull-right">Booking Number: #{{$reservation->uid}}</small>
                            </h2>
                            <hr/>
                        </div><!-- /.col -->
                    </div>
                    <div class="row invoice-info">
                    <div class="col-md-4 invoice-col">
                        Hotel Details		  <address>
                            <strong>{{general_setting()->title}}</strong><br>
                            Phone: {{general_setting()->phone}}<br/>
                            Email: {{general_setting()->email}}	<br/>
                                                  {{general_setting()->address}}
                                              </address>
                    </div><!-- /.col -->

                    @php $guests = \App\Model\Guest::where('reservation_id', $reservation->id)->get(); @endphp

                    @if(count($guests)>0)
                        <div class="col-md-4 invoice-col">
                            Guest Details		
                            <address>
                                @foreach($guests AS $guest)
                                    <strong>{{$guest->name}}</strong> @if(!is_null($guest->id_no)) ({{ $guest->id_no }}) @endif <br>
                                    @if(!is_null($guest->phone)) {{ $guest->phone }} <br> @endif
                                    @if(!is_null($guest->address)) {{ $guest->address }} <br> @endif

                                @endforeach
                            </address>
                        </div><!-- /.col -->
                    @else
                        <div class="col-md-4 invoice-col"></div>
                    @endif

                    <div class="col-md-4 invoice-col">
                        <table width="90%">
                            <tr>
                                <th><b>Room Type</b></th>
                                <th>:</th>
                                <td>{{$reservation->roomType->title}}</td>
                            </tr>
                            @if(Auth::user()->role==0)
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <td><a href="#" data-toggle="modal" data-target="#room_type"> Change Room Type</a></td>
                                </tr>
                            @endif
                            <tr>
                                <th><b>Booking Date:</b></th>
                                <th>:</th>
                                <td>{{date('Y/m/d H:i:s A',strtotime($reservation->created_at))}}</td>
                            </tr>
                            <tr>
                                <th><b>Check in </b></th>
                                <th>:</th>
                                <td >{{$reservation->check_in}}</td>
                            </tr>
                            <tr>
                                <th><b>Check out</b></th>
                                <th>:</th>
                                <td >{{$reservation->check_out}}</td>
                            </tr>
                            @if(Auth::user()->role==0||$reservation->status!='COMPLETED')
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <td><a href="#" data-toggle="modal" data-target="#checkout_model"> Add Real Checkout Time</a></td>
                                </tr>
                            @endif
                            <tr>
                                <th><b>Payment Status </b></th>
                                <th>:</th>
                                <td >
                                    <span class="badge badge-{{$reservation->paymentStatus()['color']}}">{{$reservation->paymentStatus()['status']}}</span>
                                   </td>
                            </tr>
                            <tr>
                                <th><b>Reservation Status </b></th>
                                <th>:</th>
                                <td ><span class="badge badge-{{$reservation->statusClass()}}">{{$reservation->status}}</span></td>
                            </tr>
                            <tr>
                                <th><b>Adults</b></th>
                                <th>:</th>
                                <td >{{$reservation->adults}} Person</td>
                            </tr>
                            <tr>
                                <th><b>Kids </b></th>
                                <th>:</th>
                                <td >{{$reservation->kids}} Person</td>
                            </tr>
                            <tr>
                                <th><b>Hours </b></th>
                                <th>:</th>
                                @php $hours = (strtotime($reservation->check_out) - strtotime($reservation->check_in))/3600; 
                                
                                    if(fmod($hours,1)!=0){
                                        $minutes = number_format(fmod($hours,1)*60, 0, '.', '');
                                        $hours = floor($hours/1).":".$minutes;
                                    }
                                    
                                @endphp
                                <td >{{ $hours }}</td>
                            </tr>
                        </table>
                    </div><!-- /.col -->
                    </div>
                    <!-- Table row -->
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <p class="lead text-info">Room list</p>
                            <table class=" table-sm w-100">
                                <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Room</th>
                                    <td align="right"><b>Price</b></td>
                                </tr>
                                </thead>
                                <tbody>
                                @php($i=0)
                                @foreach($reservation->night->groupBy('date') as $key=>$night)
                                    @php($i++)
                                <tr>
                                    <td>{{ $i }}.</td>
                                    <td>{{$key}}</td>
                                    <td>

                                    {{implode(' , ',$night->pluck('room.number')->toArray())}}
                                    </td>
                                    <td align="right">{{number_format($night->sum('price'),2)}} {{general_setting()->cur}}</td>

                                </tr>
                                @endforeach
                                <tr class="border-top">
                                    <td colspan="3"><b>Total Price</b></td>
                                    <td align="right"> <b> {{number_format($reservation->totalNightPrice(),2)}} {{general_setting()->cur}}</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    
                    @if($reservation->tax->count())
                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-md-12">
                            <p class="lead text-info">Taxes</p>
                            <div class="table-responsive">
                                <table class=" table-sm w-100">
                                    @foreach($reservation->tax as $key=>$tax)
                                    <tr>
                                        <td >{{$key+1}}.</td>
                                        <td>{{$tax->tax->name}}</td>
                                        <td>{{$tax->value}} {{$tax->type === 'PERCENTAGE'?'%':general_setting()->cur}}</td>
                                        <td class="text-right">{{number_format($tax->price,2)}} {{general_setting()->cur}}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="border-top">
                                        <td colspan="3" align=""><b>Total Tax</b></td>
                                        <td class="text-right"><b>{{number_format($reservation->totalTax(),2)}} {{general_setting()->cur}}</b></td>
                                    </tr>
                                </table>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                        @endif
                        
                    @if($reservation->paidService->count())
                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-md-12">
                            <p class="lead text-info">Paid Service</p>
                            <div class="table-responsive">
                                <table class="table-sm w-100">
                                    <tr class="bg-light">
                                        <th>Sl</th>
                                        <th>Service</th>
                                        <th class="text-right">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-right">Total</th>
                                    </tr>

                                    <?php 
                                        $items = [];
                                        foreach($reservation->paidService->groupBy('pad_service_id') AS $item){

                                            if($item->first()->value)
                                                $items[] = [
                                                    'title' => $item->first()->service->title,
                                                    'value' => number_format($item->first()->value,2),
                                                    'qty'   => $item->sum('qty'),
                                                    'price' => number_format($item->sum('price'),2)
                                                ];
                                        }
                                    ?>

                                    @foreach($items as $key => $item)
                                    <tr>
                                        <td >{{$key+1}}.</td>
                                        <td>{{ $item['title'] }}</td>
                                        <td class="text-right">{{ $item['value'] }} {{general_setting()->cur}}</td>
                                        <td class="text-center">{{ $item['qty'] }}</td>
                                        <td class="text-right">{{ $item['price'] }} {{general_setting()->cur}}</td>
                                    </tr>
                                    @endforeach

                                    {{-- @foreach($reservation->paidService->groupBy('pad_service_id') as $key=>$paidService)
                                    <tr>
                                        <td >{{$key}}.</td>
                                        <td>{{$paidService->first()->service->title}}</td>
                                        <td class="text-right">{{number_format($paidService->first()->value,2)}} {{general_setting()->cur}}</td>
                                        <td class="text-center">{{$paidService->sum('qty')}}</td>
                                        <td class="text-right">{{number_format($paidService->sum('price'),2)}} {{general_setting()->cur}}</td>
                                    </tr>
                                    @endforeach --}}
                                    <tr class="border-top">
                                        <td colspan="4" align=""><b>Total Paid service</b></td>
                                        <td class="text-right"><b>{{number_format($reservation->paidService->sum('price'),2)}} {{general_setting()->cur}}</b></td>
                                    </tr>
                                </table>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                        @endif
                        
                    @if($reservation->food->count())
                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-md-12">
                            <p class="lead text-info">Food & Beverage</p>
                            <div class="table-responsive">
                                <table class="table-sm w-100">
                                    <tr class="bg-light">
                                        <th>Sl</th>
                                        <th>Food/ Beverage</th>
                                        <th class="text-right">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-right">Total</th>
                                    </tr>

                                    <?php 
                                        $items = [];
                                        foreach($reservation->food->groupBy('food_id') AS $item){
                                            $items[] = [
                                                'title' => $item->first()->food->title,
                                                'value' => number_format($item->first()->value,2),
                                                'qty'   => $item->sum('qty'),
                                                'price' => number_format($item->sum('price'),2)
                                            ];
                                        }
                                    ?>

                                    @foreach($items as $key => $item)
                                    <tr>
                                        <td >{{$key+1}}.</td>
                                        <td>{{ $item['title'] }}</td>
                                        <td class="text-right">{{ $item['value'] }} {{general_setting()->cur}}</td>
                                        <td class="text-center">{{ $item['qty'] }}</td>
                                        <td class="text-right">{{ $item['price'] }} {{general_setting()->cur}}</td>
                                    </tr>
                                    @endforeach

                                    {{-- @foreach($reservation->food->groupBy('food_id') as $key => $foodBeverage)
                                        <tr>
                                            <td >{{$key}}.</td>
                                            <td>{{$foodBeverage->first()->food->title}}</td>
                                            <td class="text-right">{{number_format($foodBeverage->first()->value,2)}} {{general_setting()->cur}}</td>
                                            <td class="text-center">{{$foodBeverage->sum('qty')}}</td>
                                            <td class="text-right">{{number_format($foodBeverage->sum('price'),2)}} {{general_setting()->cur}}</td>
                                        </tr>
                                    @endforeach --}}
                                    <tr class="border-top">
                                        <td colspan="4" align=""><b>Total Food & Beverage</b></td>
                                        <td class="text-right"><b>{{number_format($reservation->food->sum('price'),2)}} {{general_setting()->cur}}</b></td>
                                    </tr>
                                </table>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                        @endif
                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-md-12">

                            <div class="table-responsive">
                                <table class="table-sm w-100">
                                    <tr>
                                        <td colspan="3" align=""><b>Discount</b></td>
                                        {{-- <td class="text-right"><b>{{number_format($reservation->discount(),2)}} {{general_setting()->cur}}</b></td> --}}
                                        <td class="text-right"><b>{{number_format($reservation->only_discount(),2)}} {{general_setting()->cur}}</b></td>
                                    </tr>
                                </table>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-md-12">

                            <div class="table-responsive">
                                <table class="table-sm w-100">
                                    <tr>
                                        <td colspan="3" align=""><b>Payable Amount</b></td>
                                        <td class="text-right"><b>{{number_format($reservation->payable(),2)}} {{general_setting()->cur}}</b></td>
                                    </tr>
                                </table>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-md-12">
                            <p class="lead text-info">Payment</p>
                            <div class="table-responsive">
                                <table class="table-sm w-100">
                                    <thead>
                                    <tr class="bg-light">
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Transaction</th>
                                        <th>Method</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($reservation->payment as $key=>$payment)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$payment->created_at}}</td>
                                            <td>{{$payment->trx}}</td>
                                            <td>{{$payment->gateway->name}}</td>
                                            <td class="text-right"> {{number_format($payment->amount)}} {{general_setting()->cur}}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-danger">No Payment!</td>
                                        </tr>
                                    @endforelse
                                    <tr class="border-top">
                                        <td colspan="4" align=""><b>Total Payment</b></td>
                                        <td class="text-right"><b>{{number_format($reservation->payment->sum('amount'),2)}} {{general_setting()->cur}}</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" align=""><b>Due</b></td>
                                        <td class="text-right"><b>{{number_format($reservation->payable()-$reservation->payment->sum('amount'),2)}} {{general_setting()->cur}}</b></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                </div>
                <div role="tabpanel" class="tab-pane fade" id="Payments">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="mt-2  text-tsk">PAYMENT LIST</h4>
                            <table class="table table-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Transaction</th>
                                    <th>Method</th>
                                    <th class="text-right">Amount</th>
                                    @if(Auth::user()->role==0||$reservation->status!='COMPLETED')
                                        <th class="text-right">Action</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($reservation->payment as $key=>$payment)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$payment->created_at}}</td>
                                        <td>{{$payment->trx}}</td>
                                        <td>{{$payment->gateway->name}}</td>
                                        <td class="text-right">{{general_setting()->cur_sym}} {{number_format($payment->amount)}}</td>
                                        @if(Auth::user()->role==0||$reservation->status!='COMPLETED')
                                            <td class="text-right">
                                                <a href="#" class="btn btn-sm btn-tsk" onclick="confirm('Are you sure cancel this payment?')?$('#payment_delete_form_{{$payment->id}}').submit():false"><i class="fa fa-trash danger"></i></a>
                                                <form action="{{route('backend.admin.reservation.remove_payment',$payment->id)}}" method="post" id="payment_delete_form_{{$payment->id}}">@csrf</form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-danger">No Payment!</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12">
                            <h4 class="mt-2  text-tsk">TAXI LIST</h4>
                            <table class="table table-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th class="text-right">Amount</th>
                                    @if(Auth::user()->role==0||$reservation->status!='COMPLETED')
                                        <th class="text-right">Action</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($reservation->taxi as $key=>$taxi)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$taxi->created_at}}</td>
                                        <td class="text-right">{{general_setting()->cur_sym}} {{number_format($taxi->price)}}</td>
                                        @if(Auth::user()->role==0||$reservation->status!='COMPLETED')
                                            <td class="text-right">
                                                <a href="#" class="btn btn-sm btn-tsk" onclick="confirm('Are you sure cancel this taxi?')?$('#taxi_delete_form_{{$taxi->id}}').submit():false"><i class="fa fa-trash danger"></i></a>
                                                <form action="{{route('backend.admin.reservation.new.remove_taxi',$taxi->id)}}" method="post" id="taxi_delete_form_{{$taxi->id}}">@csrf</form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-danger">No Taxi Added!</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12">
                            <h4 class="mt-2  text-tsk">DISCOUNT LIST</h4>
                            <table class="table table-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th class="text-right">Amount</th>
                                    @if(Auth::user()->role==0||$reservation->status!='COMPLETED')
                                        <th class="text-right">Action</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($reservation->discounts as $key=>$discount)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$discount->created_at}}</td>
                                        <td>{{$discount->type}}</td>
                                        @if($discount->type=='FIXED')
                                            <td class="text-right">{{general_setting()->cur_sym}} {{number_format($discount->value)}}</td>
                                        @else
                                            <td class="text-right">{{number_format($discount->value)}} %</td>
                                        @endif

                                        @if(Auth::user()->role==0||$reservation->status!='COMPLETED')
                                            <td class="text-right">
                                                <a href="#" class="btn btn-sm btn-tsk" onclick="confirm('Are you sure cancel this discount?')?$('#discount_delete_form_{{$discount->id}}').submit():false"><i class="fa fa-trash danger"></i></a>
                                                <form action="{{route('backend.admin.reservation.new.remove_discount',$discount->id)}}" method="post" id="discount_delete_form_{{$discount->id}}">@csrf</form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-danger">No Discount Added!</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="Room">
                    <h4 class="mt-2 text-tsk">ROOM LIST</h4>
                    <table class="table table-sm table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Room</th>
                            <th>Floor</th>
                            {{-- <th class="text-right">Action</th> --}}
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($reservation->night as $key=>$night)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$night->date}}</td>
                            <td>{{optional($night->room)->number}}</td>

                            <td>
                                @if($night->room)
                                    {{$night->room->floor->name}}

                                    @endif</td>
                            {{-- <td class="text-right">
                                <a href="#" class="btn btn-sm btn-tsk" onclick="confirm('Are you sure cancel this room?')?$('#room_delete_form_{{$night->id}}').submit():false"><i class="fa fa-trash danger"></i></a>
                                <form action="{{route('backend.admin.reservation.cancel_room',$night->id)}}" method="post" id="room_delete_form_{{$night->id}}">@csrf</form>
                            </td> --}}
                        </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-danger">No allocate room!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="Service">
                    <h4 class="mt-2 text-tsk">SERVICE LIST</h4>
                    <table class="table table-sm table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Service</th>
                            <th>Staff</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-right">Price</th>
                            <th class="text-right">Total</th>
                            @if(Auth::user()->role==0||$reservation->status!='COMPLETED')
                                <th class="text-right">Action</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($reservation->paidService as $key=>$serv)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{ date("Y-m-d H:i", strtotime($serv->date)) }}</td>
                            <td>{{$serv->service->title}}</td>
                            <td>{{$serv->staff->fullname}}</td>
                            <td class="text-center">{{$serv->qty}}</td>
                            <td class="text-right">{{general_setting()->cur_sym}}{{number_format($serv->value,2)}}</td>
                            <td class="text-right">{{general_setting()->cur_sym}}{{number_format($serv->price,2)}}</td>
                            @if(Auth::user()->role==0||$reservation->status!='COMPLETED')
                                <td class="text-right">
                                    <form action="{{route('backend.admin.reservation.remove_service',$serv->id)}}" method="post" id="serv_delete_form_{{$serv->id}}">@csrf</form>
                                    <a href="#" class="btn btn-sm btn-tsk" onclick="confirm('Are you sure remove this service?')?$('#serv_delete_form_{{$serv->id}}').submit():false"><i class="fa fa-trash danger"></i></a>
                                </td>
                            @endif
                        </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-danger">No service!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="Food">
                    <h4 class="mt-2 text-tsk">Food & Beverage List</h4>
                    <table class="table table-sm table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Food</th>
                            <th>Staff</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-right">Price</th>
                            <th class="text-right">Total</th>
                            @if(Auth::user()->role==0||$reservation->status!='COMPLETED')
                                <th class="text-right">Action</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($reservation->food as $key=>$serv)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{ date("Y-m-d H:i", strtotime($serv->date)) }}</td>
                            <td>{{$serv->food->title}}</td>
                            <td>{{$serv->staff->full_name}}</td>
                            <td class="text-center">{{$serv->qty}}</td>
                            <td class="text-right">{{general_setting()->cur_sym}}{{number_format($serv->value,2)}}</td>
                            <td class="text-right">{{general_setting()->cur_sym}}{{number_format($serv->price,2)}}</td>
                            @if(Auth::user()->role==0||$reservation->status!='COMPLETED')
                                <td class="text-right">
                                    <form action="{{route('backend.admin.reservation.remove_food',$serv->id)}}" method="post" id="serv_delete_form_{{$serv->id}}">@csrf</form>
                                    <a href="#" class="btn btn-sm btn-tsk" onclick="confirm('Are you sure remove this service?')?$('#serv_delete_form_{{$serv->id}}').submit():false"><i class="fa fa-trash danger"></i></a>
                                </td>
                            @endif
                        </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-danger">No food!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="Logs">
                    <h4 class="mt-2 text-tsk">Logs List</h4>
                    <table class="table table-sm table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>User</th>
                            <th>Item</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reservation->logs as $key=>$log)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$log->created_at}}</td>
                            <td>{{ ucfirst($log->user->full_name) }}</td>
                            <td>{{ $log->log }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="print">
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <h2 class="page-header">
                                <img src="{{general_setting()->logo}}" style="max-height: 100px" /></i> <small class="pull-right">Booking Number: #{{$reservation->uid}}</small>
                            </h2>
                            <hr/>
                        </div><!-- /.col -->
                    </div>
                    <div class="row invoice-info">
                        <div class="col-md-4 invoice-col">
                            Hotel Details		  <address>
                                <strong>{{general_setting()->title}}</strong><br>
                                Phone: {{general_setting()->phone}}<br/>
                                Email: {{general_setting()->email}}	<br/>
                                {{general_setting()->address}}
                            </address>
                        </div><!-- /.col -->


                        @if(count($guests)>0)
                            <div class="col-md-4 invoice-col">
                                Guest Details		
                                <address>
                                    @foreach($guests AS $guest)
                                        <strong>{{$guest->name}}</strong> @if(!is_null($guest->id_no)) ({{ $guest->id_no }}) @endif <br>
                                        @if(!is_null($guest->phone)) {{ $guest->phone }} <br> @endif
                                        @if(!is_null($guest->address)) {{ $guest->address }} <br> @endif

                                    @endforeach
                                </address>
                            </div><!-- /.col -->
                        @else
                            <div class="col-md-4 invoice-col"></div>
                        @endif

                        {{-- <div class="col-md-4 invoice-col">
                            Guest Details					  <address>

                                <strong>{{$reservation->guests()}}</strong>

                                <strong>{{$reservation->guest->full_name}}</strong><br>
                                {{$reservation->guest->address}}<br/>
                                Phone: {{$reservation->guest->phone}}<br/>
                                Email: {{$reservation->guest->email}}
                            					  </address> 
                        </div><!-- /.col --> --}}
                        <div class="col-md-4 invoice-col">
                            <table width="90%">
                                <tr>
                                    <th><b>Room Type</b></th>
                                    <th>:</th>
                                    <td>{{$reservation->roomType->title}}</td>
                                </tr>
                                <tr>
                                    <th><b>Booking Date:</b></th>
                                    <th>:</th>
                                    <td>{{date('Y/m/d H:i:s A',strtotime($reservation->created_at))}}</td>
                                </tr>
                                <tr>
                                    <th><b>Check in </b></th>
                                    <th>:</th>
                                    <td >{{$reservation->check_in}}</td>
                                </tr>
                                <tr>
                                    <th><b>Check out</b></th>
                                    <th>:</th>
                                    <td >{{$reservation->check_out}}</td>
                                </tr>
                                <tr>
                                    <th><b>Payment Status </b></th>
                                    <th>:</th>
                                    <td >
                                        <span class="badge badge-{{$reservation->paymentStatus()['color']}}">{{$reservation->paymentStatus()['status']}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><b>Reservation Status </b></th>
                                    <th>:</th>
                                    <td ><span class="badge badge-{{$reservation->statusClass()}}">{{$reservation->status}}</span></td>
                                </tr>
                                <tr>
                                    <th><b>Adults</b></th>
                                    <th>:</th>
                                    <td >{{$reservation->adults}} Person</td>
                                </tr>
                                <tr>
                                    <th><b>Kids </b></th>
                                    <th>:</th>
                                    <td >{{$reservation->kids}} Person</td>
                                </tr>
                                <tr>
                                    <th><b>Hours </b></th>
                                    <th>:</th>
                                    <td >{{ $hours }}</td>
                                </tr>
                            </table>
                        </div><!-- /.col -->
                    </div>
                    <!-- Table row -->
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class=" table-sm w-100">

                                <tbody>

                                <tr class="">
                                    <td ><b>Room Charges</b></td>
                                    <td align="right"> <b> {{number_format($reservation->totalNightPrice(),2)}} {{general_setting()->cur}}</b></td>
                                </tr>
                                <tr class="">
                                    <td ><b>Taxes</b></td>
                                    <td align="right"> <b> {{number_format($reservation->totalTax(),2)}} {{general_setting()->cur}}</b></td>
                                </tr>
                                <tr class="">
                                    <td ><b>Total Paid Service</b></td>
                                    <td align="right"> <b> {{number_format($reservation->paidService->sum('price'),2)}} {{general_setting()->cur}}</b></td>
                                </tr>
                                <tr class="">
                                    <td ><b>Total Food/ Beverage</b></td>
                                    <td align="right"> <b> {{number_format($reservation->food->sum('price'),2)}} {{general_setting()->cur}}</b></td>
                                </tr>
                                <tr class="">
                                    <td ><b>Discount</b></td>
                                    {{-- <td align="right"> <b> {{number_format($reservation->discount(),2)}} {{general_setting()->cur}}</b></td> --}}
                                    <td align="right"><b>{{number_format($reservation->only_discount(),2)}} {{general_setting()->cur}}</b></td>
                                </tr>
                                <tr class="border-top">
                                    <td ><b>Payable Amount</b></td>
                                    <td align="right"> <b> {{number_format($reservation->payable(),2)}} {{general_setting()->cur}}</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-md-12">
                            <p class="lead text-info">Payment</p>
                            <div class="table-responsive">
                                <table class="table-sm w-100">
                                    <thead>
                                    <tr class="bg-light">
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Transaction</th>
                                        <th>Method</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($reservation->payment as $key=>$payment)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$payment->created_at}}</td>
                                            <td>{{$payment->trx}}</td>
                                            <td>{{$payment->gateway->name}}</td>
                                            <td class="text-right"> {{number_format($payment->amount)}} {{general_setting()->cur}}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-danger">No Payment!</td>
                                        </tr>
                                    @endforelse
                                    <tr class="border-top">
                                        <td colspan="4" align=""><b>Total Payment</b></td>
                                        <td class="text-right"><b>{{number_format($reservation->payment->sum('amount'),2)}} {{general_setting()->cur}}</b></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class=" table-sm w-100">

                                <tbody>
                                <tr class="">
                                    <td ><b>Due</b></td>
                                    <td align="right"> <b> {{number_format($reservation->payable()-$reservation->payment->sum('amount'),2)}} {{general_setting()->cur}}</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div>
            </div>
        </div>
    </div>
@include('backend.admin.reservation.payment_model',['data'=>$reservation])
@include('backend.admin.reservation.room_type_model',['data'=>$reservation])
@include('backend.admin.reservation.checkout_model',['data'=>$reservation])
@include('backend.admin.reservation.service_model',['data'=>$reservation])
@include('backend.admin.reservation.food_model',['data'=>$reservation])
@include('backend.admin.reservation.taxi_model',['data'=>$reservation])
@include('backend.admin.reservation.discount_model',['data'=>$reservation])
@endsection
@section('script')
    <script type="text/javascript">

        var token = "{{ Session::token() }}";
        var food_id = 0;
        var service_id = 0;

        $('#service_date').datetimepicker({
            // minView: 2,
            format: 'yyyy-mm-dd hh:ii',
            // startDate: new Date(),
            endDate: new Date(),
            weekStart: 1,
            todayBtn:  1,
            todayHighlight: 1,
            showMeridian: 1,
            startView: 2,
            forceParse: 0,                    
            autoclose: true
        });

        $('#checkout_time').datetimepicker({
            // minView: 2,
            format: 'yyyy-mm-dd hh:ii',
            // startDate: new Date(),
            endDate: new Date(),
            weekStart: 1,
            todayBtn:  1,
            todayHighlight: 1,
            showMeridian: 1,
            startView: 2,
            forceParse: 0,                    
            autoclose: true
        });

        $('#food_date').datetimepicker({
            // minView: 2,
            format: 'yyyy-mm-dd hh:ii',
            // startDate: new Date(),
            endDate: new Date(),
            weekStart: 1,
            todayBtn:  1,
            todayHighlight: 1,
            showMeridian: 1,
            startView: 2,
            forceParse: 0,                    
            autoclose: true
        });

        $(document).ready(function() {

            $('.food-list-ajax').select2({
                ajax: {
                    method: 'post',
                    delay: 250,
                    url: "{{ route('admin.reservation.new.food_select') }}",
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            searchTerm: params.term,
                            type: 'public',
                            _token: token
                        }
                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function(data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data.items
                        };
                    }
                }
            });

            $('.food-list-ajax').on('select2:select', function(e) {
                food_id = e.params.data.id;
            });

            $('.service-list-ajax').select2({
                ajax: {
                    method: 'post',
                    delay: 250,
                    url: "{{ route('admin.reservation.new.service_select') }}",
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            searchTerm: params.term,
                            type: 'public',
                            _token: token
                        }
                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function(data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data.items
                        };
                    }
                }
            });

            $('.service-list-ajax').on('select2:select', function(e) {
                service_id = e.params.data.id;
            });
        });

        // $(document).ready(function () {
        //     $('#payment_date').datepicker({
        //         uiLibrary: 'bootstrap4',
        //         format: 'yyyy/mm/dd',
        //         iconsLibrary: 'fontawesome',
        //         footer: true, modal: true,
        //         theme:'green',
        //     });
        // });

        // $(document).ready(function () {
        //     $('#service_date').datetimepicker({
        //         uiLibrary: 'bootstrap4',
        //         format: 'yyyy/mm/dd',
        //         iconsLibrary: 'fontawesome',
        //         //footer: true, 
        //         modal: true,
        //         theme:'green',
        //     });
        // });

        // $(document).ready(function () {
        //     $('#food_date').datetimepicker({
        //         uiLibrary: 'bootstrap4',
        //         format: 'yyyy/mm/dd',
        //         iconsLibrary: 'fontawesome',
        //         //footer: true, 
        //         modal: true,
        //         theme:'green',
        //     });
        // });

        // $(document).ready(function() {
        //     $('.js-example-basic-single').select2();
        // });
    </script>

    <script type="text/javascript">

        discount_calc();

        $( "#discount_type" ).change(function() {
            discount_calc();
        });

        $( "#discount_value" ).keyup(function() {
            discount_calc();
        });

        function discount_calc(){
            var discount_type = $( "#discount_type" ).val();
            var discount_value = $( "#discount_value" ).val();
            var current_payable = "{{ $reservation->payable()-$reservation->payment->sum('amount') }}";
            var new_payable = 0;

            if(discount_type=="PERCENTAGE"){
                new_payable = Number(current_payable)-(Number(current_payable)*Number(discount_value)/100);
            }
            else{
                new_payable = Number(current_payable)-Number(discount_value);
            }

            $( "#new_payable" ).val(numberWithCommas(new_payable.toFixed(2)));
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

    </script>




@endsection