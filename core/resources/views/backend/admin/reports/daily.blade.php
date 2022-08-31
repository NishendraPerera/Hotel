@extends('backend.master')
@section('title',"Reservation")
@section('content')
        <div class="card">
            <div class="card-header bg-white float-right">
                <h2>Daily Report

                    {{-- <a class="btn btn-tsk float-md-right" href="{{route('backend.admin.reservation.create')}}"><i class="fa fa-plus"></i> Add Reservation</a>
                    <div class="btn-group float-md-right mr-2">
                        <a class="btn btn-outline-secondary {{active_menu([route('backend.admin.reservation')],'active')}}" href="{{route('backend.admin.reservation')}}">All</a>
                        <a class="btn btn-outline-secondary {{active_menu([route('backend.admin.reservation','online')],'active')}}" href="{{route('backend.admin.reservation','online')}}">Online</a>
                        <a class="btn btn-outline-secondary {{active_menu([route('backend.admin.reservation','offline')],'active')}}" href="{{route('backend.admin.reservation','offline')}}">Offline</a>
                    </div> --}}
                </h2>
            </div>

            <div class="card-body text-dark">
                <div class="row">
                    <div class="col-sm-4">
                        <input type="text" class="form-control" value="{{ date('Y-m-d') }}" placeholder="Start Date" id="start" readonly="">
                    </div>
                </div>


                <div class="row" style="margin-top: 10px;">
                    <div class="col-sm-3">
                        <button type="submit" id="update" class="btn btn-success">Update</button>
                    </div>
                </div>

                <form class="form-inline" style="margin-top: 30px;">                        
                    <div class="form-group">
                        <div class="col-sm-12">
                        <p class="form-control-static" id="from"></p>
                        </div>
                    </div>    

                    <div class="form-group">
                        <div class="col-sm-12">
                        <p class="form-control-static" id="to"></p>
                        </div>
                    </div> 

                </form>

                <form class="form-inline" >                        
                    <div class="form-group">
                        <div class="col-sm-12">
                        <p class="form-control-static" id="tableRoomTotal"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                        <p class="form-control-static" id="tableFoodTotal"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                        <p class="form-control-static" id="tableServiceTotal"></p>
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <div class="col-sm-12">
                        <p class="form-control-static" id="tableDiscount"></p>
                        </div>
                    </div> --}}
                </form>

                <form class="form-inline" >                        
                    <div class="form-group">
                        <div class="col-sm-12">
                        <p class="form-control-static" id="tableTotal"></p>
                        </div>
                    </div>
                </form>

                <div class="p-0 table-responsive" style="padding:2% !important;">
                    <table class="table table-sm table-condensed mb-0">
                        <thead class="bg-tsk-o-1">
                        <tr>
                            <th>Reservation Number</th>
                            {{-- <th>Service Date</th> --}}
                            {{-- <th>Room</th> --}}
                            <th>Guest</th>
                            <th>Room Charges</th>
                            <th>Food Charges</th>
                            <th>Service Charges</th>
                            {{-- <th>Room Type</th>
                            <th>Check in</th>
                            <th>Check out</th> --}}
                            {{-- <th>Staff</th> --}}
                            {{-- <th>Food/ Beverage</th> --}}
                            {{-- <th>Rate</th> --}}
                            {{-- <th>Qty</th> --}}
                            {{-- <th>Price</th> --}}
                            {{-- <th class="text-center">Payment Status</th>
                            <th class="text-center">Reservation Status</th> --}}
                            <th class="text-right" style="width: 50px">Action</th>
                        </tr>
                        </thead>
                        <tbody>
    
                        </tbody>
                    </table>
                </div>
                
            </div>

        </div>

        @section('script')
            <script type="text/javascript">
                $(function () {

                    var table = $('.table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {url : "{{ route('admin.reports.daily_list') }}"
                        , data : function(d) { d.starting = document.getElementById("start").value; }
                        , dataSrc :function(json){ $('#from').html("<b>From: </b>"+json.from); $('#to').html("<b>To: </b>"+json.to); $('#tableRoomTotal').html("<b>Room Total: </b>"+json.room_total); $('#tableFoodTotal').html("<b>Food Total: </b>"+json.food_total); $('#tableServiceTotal').html("<b>Service Total: </b>"+json.service_total); $('#tableDiscount').html("<b>Disount Total: </b>("+json.discount_total+")"); $('#tableTotal').html("<b>Total: </b>"+json.total); return json.data; } 
                        },
                        "order": [[ 1, "desc" ]],
                        columns: [
                            // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                            {data: 'uid', name: 'uid'},
                            // {data: 'date', name: 'date'},
                            // {data: 'room', name: 'room'},
                            {data: 'username', name: 'username'},
                            {data: 'room', name: 'room', className: "dt-body-right"},
                            {data: 'food', name: 'food', className: "dt-body-right"},
                            {data: 'service', name: 'service', className: "dt-body-right"},
                            // {data: 'room_type', name: 'room_type'},
                            // {data: 'check_in', name: 'check_in'},
                            // {data: 'check_out', name: 'check_out'},
                            // {data: 'online', name: 'online'},
                            // {data: 'payment_status', name: 'payment_status', searchable: false, className: "dt-body-center"},
                            // {data: 'status', name: 'status', searchable: false, className: "dt-body-center"},
                            // {data: 'staff_name', name: 'staff_name'},
                            // {data: 'food_name', name: 'food_name'},
                            // {data: 'value', name: 'value', className: "dt-body-right"},
                            // {data: 'qty', name: 'qty', className: "dt-body-center"},
                            // {data: 'price', name: 'price', className: "dt-body-right"},
                            {data: 'action', name: 'action', orderable: false, searchable: false, className: "dt-body-center"},
                        ]
                    }); 

                    $("#update").click(function(event) {
                        event.preventDefault();

                        var startDate = $('#start').val();
                        var endDate = $('#end').val();

                        if(startDate==""){
                            $('#start').val("");
                            $('#end').val("");
                        }

                        table.ajax.reload();
                    });

                });

                $('#start').datetimepicker({
                    minView: 2,
                    format: 'yyyy-mm-dd',
                    startDate: '2017-03-01',
                    endDate: new Date(),
                    weekStart: 1,
                    todayBtn:  1,
                    todayHighlight: 1,
                    showMeridian: 1,
                    startView: 2,
                    forceParse: 0,                    
                    autoclose: true
                });
            
            </script>
        @endsection

@endsection