@extends('backend.master')
@section('title',"Guest")
@section('content')
        <div class="card">
            <div class="card-header bg-white">
                <h2>Guests
                    {{-- <a class="btn btn-tsk float-right" href="{{route('backend.admin.guests.create')}}"><i class="fa fa-plus"></i> Create Guest</a> --}}

                </h2>
            </div>

            {{-- style="padding:2% !important;" --}}
            <div class="card-body p-0" style="padding:2% !important;">
                <table class="table table-sm table-condensed mb-0" >
                    <thead class="bg-tsk-o-1">
                    <tr>
                        <th>Name</th>
                        <th>ID No.</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Reservation</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th class="text-center">View</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{-- @foreach($guests as $key=>$guest)
                    <tr>
                        <td><a href="{{route('backend.admin.guests.view',$guest->id)}}">{{$guest->full_name}}</a></td>
                        <td>{{$guest->email}}</td>
                        <td>{{$guest->phone}}</td>
                        <td><span class="badge {{$guest->vip?'badge-success':'badge-danger'}}">{{$guest->vip?'VIP':''}}</span></td>
                        <td><span class="badge {{$guest->status?'badge-success':'badge-danger'}}">{{$guest->status?'Active':'Inactive'}}</span></td>
                        <td class="text-right">
                            <div class="btn-group btn-group-sm">
                                <a href="{{route('backend.admin.guests.view',$guest->id)}}" class="btn btn-outline-tsk"><i class="fa fa-eye"></i> </a>
                            </div>

                        </td>
                    </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
            {{-- <div class="pagination-center">
                {{ $guests->links() }}
            </div> --}}
        </div>

        @section('script')
            <script type="text/javascript">
                $(function () {
                    var table = $('.table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('backend.admin.guest_lists') }}",
                        columns: [
                            // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                            {data: 'name', name: 'name'},
                            {data: 'id_no', name: 'id_no'},
                            {data: 'phone', name: 'phone'},
                            {data: 'address', name: 'address'},
                            {data: 'number', name: 'number'},
                            {data: 'check_in', name: 'check_in'},
                            {data: 'check_out', name: 'check_out'},
                            // {data: 'action', name: 'action', orderable: false, searchable: false, className: "text-center"},
                        ],
                        columnDefs : [ { targets : [7], render : function (data, type, row) { var url = "{{ url('/') }}"; return "<a href='"+url+"/admin/reservation/"+row.reservation_id+"/view"+"' target='_blank' class='btn btn-outline-tsk'><i class='fa fa-eye'></i>" } }],
                    });             
                });
            
            </script>
        @endsection

@endsection