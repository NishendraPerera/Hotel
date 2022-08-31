<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{general_setting()->title}} | @yield('title','admin')</title>
    <link rel="shortcut icon" href="{{general_setting()->favicon}}">
    <link rel="stylesheet" href="{{asset('assets/plugin/bootstrap-4.0.0/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugin/font-awesome/css/font-awesome.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/bootadmin.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugin/toastr/build/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugin/bootstrap-toggle/css/bootstrap2-toggle.min.css')}}">

    <link rel="stylesheet" href="{{asset('assets/plugin/select2/dist/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugin/select2-bootstrap-theme/dist/select2-bootstrap.min.css')}}">
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" /> --}}
    

@if(Route::currentRouteName()=='backend.admin.reservation.create'||Route::currentRouteName()=='admin.reports.reservation'||Route::currentRouteName()=='backend.admin.reservation.view'||Route::currentRouteName()=='admin.reports.food'||Route::currentRouteName()=='admin.reports.service'||Route::currentRouteName()=='admin.reports.payment'||Route::currentRouteName()=='backend.admin.dashboard'||Route::currentRouteName()=='admin.reports.sale'||Route::currentRouteName()=='admin.reports.taxi'||Route::currentRouteName()=='admin.reports.daily')
    <link href="{{asset('assets/datetime/bootstrap-datetimepicker.css')}}"  rel="stylesheet">
@else
    <link rel="stylesheet" href="{{asset('assets/plugin/gijgo-combined-1.9.11/css/gijgo.min.css')}}">
@endif



@if(Route::currentRouteName()=='backend.admin.reservation.create')
    <link href="{{asset('assets/guest_table/style.css')}}"  rel="stylesheet">
@endif
    
    <link rel="stylesheet" href="{{asset('assets/plugin/date-time/mdtimepicker.min.css')}}">
    <link href="{{url('/')}}/assets/backend/css/color.php?color={{general_setting()->color}}" rel="stylesheet">

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" /> --}}
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet"> 

    @if(Route::currentRouteName() == 'backend.admin.reservation.create')
        {{-- <link href="{{asset('assets/eon/style.css')}}"  rel="stylesheet">  --}}
        {{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" /> --}}
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">

    @yield('style')
</head>