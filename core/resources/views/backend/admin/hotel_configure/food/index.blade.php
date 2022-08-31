@extends('backend.master')
@section('title',"Food & Beverage")
@section('content')
        <div class="card">
            <div class="card-header bg-white">
                <h2>Food & Beverage
                    <a class="btn btn-tsk float-right" href="{{route('backend.admin.food.create')}}"><i class="fa fa-plus"></i> Add Food & Beverage</a>

                </h2>
            </div>
            <div class="card-body p-0" style="padding:2% !important;">
                <table class="table table-sm table-condensed mb-0">
                    <thead class="bg-tsk-o-1 text-center" >
                    <tr>
                        {{-- <th>Sl. No.</th> --}}
                        {{-- <th>Icon</th> --}}
                        <th>Title</th>
                        <th >Price ({{general_setting()->cur}})</th>
                        <th>Status</th>
                        <th >Action</th>
                    </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>

        @section('script')
            <script type="text/javascript">
                $(function () {
                    var table = $('.table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('backend.admin.food_lists') }}",
                        columns: [
                            // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                            {data: 'title', name: 'title'},
                            {data: 'price', name: 'price', className: "dt-body-right"},
                            {data: 'status', name: 'status', searchable: false, className: "dt-body-center"},
                            {data: 'action', name: 'action', orderable: false, searchable: false, className: "dt-body-center"},
                        ]
                    });             
                });
            
            </script>
        @endsection

@endsection