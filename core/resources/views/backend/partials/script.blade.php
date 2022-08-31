<script src="{{asset('assets/backend/js/jquery-3.2.1.min.js')}}"></script>
<script src="{{asset('assets/plugin/bootstrap-4.0.0/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/backend/js/bootadmin.min.js')}}"></script>
<script src="{{asset('assets/plugin/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
<script src="{{asset('assets/plugin/niceditor/nicEdit.js')}}"></script>

{{-- <script src="{{asset('assets/plugin/select2/dist/js/select2.min.js')}}"></script>. --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<!-- use the latest vue-select release -->
{{-- <script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.15/lodash.js"></script> --}}


@if(Route::currentRouteName()=='backend.admin.reservation.create'||Route::currentRouteName()=='backend.admin.reservation.view'||Route::currentRouteName()=='admin.reports.reservation'||Route::currentRouteName()=='admin.reports.food'||Route::currentRouteName()=='admin.reports.service'||Route::currentRouteName()=='admin.reports.payment'||Route::currentRouteName()=='backend.admin.dashboard'||Route::currentRouteName()=='admin.reports.sale'||Route::currentRouteName()=='admin.reports.taxi'||Route::currentRouteName()=='admin.reports.daily')
    <script src="{{asset('assets/datetime/bootstrap-datetimepicker.js')}}"></script>
@else
    <script src="{{asset('assets/plugin/gijgo-combined-1.9.11/js/gijgo.min.js')}}"></script>
@endif

<script src="{{asset('assets/plugin/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/plugin/date-time/mdtimepicker.min.js')}}"></script>
<script src="{{asset('assets/plugin/print_this.js')}}"></script>
<script src="{{asset('assets/plugin/vue/vue.js')}}"></script>
<script src="{{asset('assets/plugin/axios/axios.js')}}"></script>
<script src="{{asset('assets/backend/js/custom.js')}}"></script>

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>

@if(Route::currentRouteName()=='backend.admin.reservation.create')
    <script src="{{asset('assets/guest_table/index.js')}}"></script>
@endif


<script>
    $(document).ready(function () {
        $('.select2').select2({
            theme: "bootstrap4"
        });
    });
</script>
<script>
    window.Laravel = @php echo json_encode([
       'csrfToken' => csrf_token(),
   ]) ; @endphp ;

    function printContent(el){
        var restorepage  = $('body').html();
        var printcontent = $('#' + el).clone();
        $('body').empty().html(printcontent);
        window.print();
        $('body').html(restorepage);
        location.reload();
    }
</script>
@yield('script')
