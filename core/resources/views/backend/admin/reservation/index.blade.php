@extends('backend.master')
@section('title',"Reservation")
@section('content')
        <div class="card">
            <div class="card-header bg-white float-right">
                <h2>Reservation

                    <a class="btn btn-tsk float-md-right" href="{{route('backend.admin.reservation.create')}}"><i class="fa fa-plus"></i> Add Reservation</a>
                </h2>
            </div>
            <div class="card-body p-0 table-responsive" style="padding:2% !important;">
                <table class="table table-sm table-condensed mb-0">
                    <thead class="bg-tsk-o-1">
                    <tr>

                        <th>Reservation Number</th>
                        <th>Reservation Date</th>
                        <th>Guest/s</th>
                        <th>Room Type</th>
                        <th>Check in</th>
                        <th>Check out</th>
                        {{-- <th>Booking Type</th> --}}
                        <th class="text-center">Payment Status</th>
                        <th class="text-center">Reservation Status</th>
                        <th class="text-right" style="width: 50px">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        {{-- @forelse($reservations as $key=>$reservation)
                        <tr>

                            <td>{{$reservation->uid}}</td>
                            <td>{{$reservation->date}}</td>
                            <td><a href="{{route('backend.admin.guests.view',$reservation->guest->id)}}">{{$reservation->guest->username}}</a></td>
                            <td>{{$reservation->roomType->title}}</td>
                            <td>{{$reservation->check_in}}</td>
                            <td>{{$reservation->check_out}}</td>
                            <td>{{$reservation->online?'Online':'Offline'}}</td>
                            <td class="text-center"><span class="badge badge-{{$reservation->paymentStatus()['color']}}">{{$reservation->paymentStatus()['status']}}</span></td>
                            <td class="text-center"><span class="badge badge-{{$reservation->statusClass()}}">{{$reservation->status === 'ONLINE_PENDING'?'PENDING':$reservation->status}}</span></td>
                            <td class="text-right">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{route('backend.admin.reservation.view',$reservation->id)}}" class="btn btn-tsk"><i class="fa fa-eye"></i> View</a>
                                </div>
                            </td>
                        </tr>
                            @empty

                            <tr>
                                <td colspan="10">No Reservation</td>
                            </tr>
                            @endforelse --}}
                    </tbody>
                </table>
                {{-- <div class="text-center ml-2">
                    {{$reservations->links()}}
                </div> --}}
            </div>
        </div>

        @section('script')
            <script type="text/javascript">
                $(function () {

                    @if(Request::url() == route('backend.admin.reservation'))
                        var url = "{{ route('backend.admin.reservation.list') }}";
                    @elseif(Request::url() == route('backend.admin.reservation', ['online']))
                        var url = "{{ route('backend.admin.reservation.list', ['online']) }}";
                    @elseif(Request::url() == route('backend.admin.reservation', ['offline']))
                        var url = "{{ route('backend.admin.reservation.list', ['offline']) }}";
                    @endif

                    var table = $('.table').DataTable({
                        processing: true,
                        serverSide: false,
                        ajax: url,
                        "order": [[ 4, "desc" ]],
                        columns: [
                            // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                            {data: 'uid', name: 'uid'},
                            {data: 'date', name: 'date'},
                            {data: 'username', name: 'username'},
                            {data: 'room_type', name: 'room_type'},
                            {data: 'check_in', name: 'check_in'},
                            {data: 'check_out', name: 'check_out'},
                            // {data: 'online', name: 'online'},
                            {data: 'payment_status', name: 'payment_status', searchable: false, className: "dt-body-center"},
                            {data: 'status', name: 'status', searchable: false, className: "dt-body-center"},
                            {data: 'action', name: 'action', orderable: false, searchable: false, className: "dt-body-center"},
                        ],
                        searchDelay: 200

                    }); 

                    

                });

                
            
            </script>
        @endsection

@endsection