@extends('backend.master')
@section('title',"Reservation")
@section('content')
        <div class="card">
            <div class="card-header bg-white float-right">
                <h2>Payment Report

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
                        <input type="text" class="form-control" value="{{ date('Y-m-d', strtotime('-1 months')) }}" placeholder="Start Date" id="start" readonly="">
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" placeholder="End Date" id="end" readonly="">
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    {{-- <div class="col-sm-4">
                        <select class="form-control" placeholder="Executive" id="customerName" name="customerName">
                            <option value="0">All Users</option>
                            @foreach($users AS $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="col-sm-4">
                        <select class="form-control" id="salesMonth" name="salesMonth">
                            <option value="0">All Months</option>
                            <option value="LOctober">Last October</option>
                            <option value="LNovember">Last November</option>
                            <option value="LDecember">Last December</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                    </select>
                    </div>
                    {{-- <div class="col-sm-4">
                        <button type="submit" id="update" class="btn btn-success">Update</button>
                    </div> --}}
                </div>

                <div class="row" style="margin-top: 10px;">

                    <div class="col-sm-4">
                        <select class="form-control" placeholder="Executive" id="method" name="method">
                            <option value="0">--All Payment Methods--</option>
                            @foreach($methods AS $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
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
                    <div class="form-group">
                        <div class="col-sm-12">
                        <p class="form-control-static" id="tableMethod" ></p>
                        </div>
                    </div>  
                    {{-- <div class="form-group">
                        <div class="col-sm-12">
                        <p class="form-control-static" id="tableBookingType" ></p>
                        </div>
                    </div> --}}
                </form>

                <form class="form-inline">                        
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
                            <th>Reservation Date</th>
                            <th>Guest</th>
                            {{-- <th>Room Type</th>
                            <th>Check in</th>
                            <th>Check out</th>
                            <th>Booking Type</th> --}}
                            <th>Method</th>
                            <th>Amount</th>
                            <th class="text-center">Payment Status</th>
                            {{-- <th class="text-center">Reservation Status</th> --}}
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
                        ajax: {url : "{{ route('admin.reports.payment_list') }}"
                        , data : function(d) { d.starting = document.getElementById("start").value; d.ending = document.getElementById("end").value; d.salesMonth = document.getElementById("salesMonth").value; d.method = document.getElementById("method").value; }
                        , dataSrc : function(json){ $('#from').html("<b>From: </b>"+json.from); $('#to').html("<b>To: </b>"+json.to); $('#tableTotal').html("<b>Total: </b>"+json.total); $('#tableMethod').html("<b>Payment Method: </b>"+json.method); return json.data;} 
                        },
                        columns: [
                            // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                            {data: 'uid', name: 'uid'},
                            {data: 'date', name: 'date'},
                            {data: 'username', name: 'username'},
                            {data: 'method', name: 'method'},
                            {data: 'amount', name: 'amount'},
                            {data: 'payment_status', name: 'payment_status', searchable: false, className: "dt-body-center"},
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

                $('#end').datetimepicker({
                    minView: 2,
                    format: 'yyyy-mm-dd',
                    endDate: new Date(),
                    weekStart: 1,
                    todayBtn:  1,
                    todayHighlight: 1,
                    showMeridian: 1,
                    startView: 2,
                    forceParse: 0,                    
                    autoclose: true
                });

                $('#start').datetimepicker().on('changeDate', function(ev){
                    $('#end').datetimepicker('setStartDate', $('#start').val());                
                    $('#end').datetimepicker('show');
                    $("#salesMonth").val("0");
                });

                $('#end').on('click', function(ev){
                    if($('#start').val()==""){
                        $('#end').datetimepicker('hide');
                        $('#start').datetimepicker('show');
                    }
                });

                $('#end').datetimepicker().on('changeDate', function(ev){
                    $("#salesMonth").val("0");
                });

                $('#salesMonth').on('change', function (e) {
                    $('#start').val("");
                    $('#end').val("");
                });

            
            </script>
        @endsection

@endsection