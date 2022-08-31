@extends('backend.master')
@section('title', 'Reservation')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/custom_page.css') }}">
@endsection
@section('content')
    <div class="card">
        <div class="card-header bg-white">
            <h2>Create Reservation
                <a class="btn btn-tsk float-right" href="{{ route('backend.admin.reservation') }}"><i
                        class="fa fa-list"></i> Reservation List</a>

            </h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 ">

                    <div id="table" class="table-editable" style="margin-top: 10px;">

                        <div class="row">
                            <div class="col-lg-offset-1 col-lg-10">
                                <table class="table" id="guest_table">
                                    <tr>
                                        <th>Name</th>
                                        <th>ID</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>
                                            <span class="table-add fa fa-plus"></span>
                                        </th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" placeholder="Name" /></td>
                                        <td><input type="text" class="form-control" placeholder="ID No" /></td>
                                        <td><input type="text" class="form-control" placeholder="Phone No" /></td>
                                        <td><input type="text" class="form-control" placeholder="Address" /></td>
                                        <td>
                                            <span class="table-remove fa fa-remove"></span>
                                        </td>
                                        <td>
                                            <span class="table-up fa fa-arrow-up"></span>
                                            <span class="table-down fa fa-arrow-down"></span>
                                        </td>
                                    </tr>

                                    <!-- This is our clonable table line -->
                                    <tr class="hide">
                                        <td><input type="text" class="form-control" placeholder="Name" /></td>
                                        <td><input type="text" class="form-control" placeholder="ID No" /></td>
                                        <td><input type="text" class="form-control" placeholder="Phone No" /></td>
                                        <td><input type="text" class="form-control" placeholder="Address" /></td>
                                        <td>
                                            <span class="table-remove fa fa-remove"></span>
                                        </td>
                                        <td>
                                            <span class="table-up fa fa-arrow-up"></span>
                                            <span class="table-down fa fa-arrow-down"></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <p id="aexport"></p>
                    </div>

                    <div class=" mb-3">
                        <div class="form-row justify-content-left ">

                            {{-- <div class="form-group col-md-4">
                                <label><strong>Guest</strong> <small class="text-danger">*</small></label><a
                                    href="{{ route('backend.admin.guests.create') }}" target="_blank" class="float-right"><i
                                        class="fa fa-plus"></i> add new</a>
                                <select class="select2 guest-list-ajax form-control form-control-lg"></select>
                            </div> --}}

                            <div class="form-group col-md-4">
                                <label><strong>Room Type</strong> <small class="text-danger">*</small></label>
                                <select id="room_type" class="form-control form-control-lg" name="room_type">
                                    <option value="0">Select</option>
                                    @foreach ($room_types as $room_type)
                                        <option value="{{ $room_type->id }}">{{ $room_type->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row justify-content-left" id="visitors_div" style="display:none;">
                            <div class="form-group col-md-4">
                                <label><strong>Adults</strong> <small class="text-danger">*</small></label>
                                <input type="text" class="form-control" placeholder="Adults" id="adults">
                                <span class="add-on"><i class="icon-th"></i></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label><strong>Kids</strong> <small class="text-danger">*</small></label>
                                <input type="text" class="form-control" placeholder="Kids" id="kids">
                            </div>
                        </div>

                        <div class="form-row justify-content-left ">
                            <div class="form-group col-md-4" id="checkin_div" style="display:none;">
                                <label><strong>Check In</strong> <small class="text-danger">*</small></label>
                                <input type="text" class="form-control" {{--
                                    value="{{ date('Y-m-d H:i', strtotime('-1 months')) }}" --}}
                                    placeholder="Check In" id="checkin" readonly="">
                                <span class="add-on"><i class="icon-th"></i></span>
                            </div>
                            <div class="form-group col-md-4" id="checkout_div" style="display:none;">
                                <label><strong>Check Out</strong> <small class="text-danger">*</small></label>
                                <input type="text" class="form-control" placeholder="Check Out" id="checkout" readonly="">
                            </div>
                        </div>
                        <div class="form-row justify-content-left ">
                            <b id="no_rooms" style="color:red; display:none;">No rooms availbale!</b>
                            <table class="table table-bordered" id="selecting_table" style="display:none; width:100%;">
                                <thead>
                                    <tr>
                                        <th scope="col">From</th>
                                        <th scope="col">To</th>
                                        <th scope="col">Room</th>
                                        <th scope="col">Price</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <div class="form-row justify-content-left ">
                            <table class="table table-bordered" id="price_table" style="display:none; width:100%;">
                                {{-- <thead> --}}
                                    <tr>
                                        <td width="65%"></td>
                                        <td width="*"></td>
                                    </tr>
                                    {{-- </thead> --}}
                            </table>
                        </div>

                        <div class="form-row justify-content-left">
                            <button type="button" id="submit" class="btn btn-success" style="display:none;">Add
                                Reservation</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
@section('script')

    <script>
        var token = "{{ Session::token() }}";

        var hours = 0;
        var base_price = 0;
        var room_type_id = 0;
        var check_in_date = 0;
        var higher_capacity = 0;
        var kids_capacity = 0;
        var guest_id = 0;
        var adults_value = 0;
        var kids_value = 0;
        var guest_list = [];

        $(document).ready(function() {

            $("#room_type").val(0);

            $('#checkin_div').hide();
            $('#checkout_div').hide();
            $('#checkin').val('');
            $('#checkout').val('');
            $('#adults').val(0);
            $('#kids').val(0);
            $('#selecting_table').hide();
            $('#price_table').hide();
            $('#no_rooms').hide();
            $('#submit').hide();

            $('.guest-list-ajax').select2({
                ajax: {
                    method: 'post',
                    delay: 250,
                    url: "{{ route('backend.admin.guest_select') }}",
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

            $('.guest-list-ajax').on('select2:select', function(e) {
                guest_id = e.params.data.id;

                if ($("#room_type").val() != 0) {
                    load_elements();
                }
            });

            $("#adults").change(function() {
                adults_value = $("#adults").val();
                kids_value = $("#kids").val();
                var total = parseInt(adults_value) + parseInt(kids_value);
                if (total > higher_capacity) {
                    $("#adults").val(0);
                    $("#kids").val(0);
                    $.alert({
                        type: 'red',
                        title: 'Error!',
                        content: "Highest capacity exceeded"
                    });
                }
            });

            $("#kids").change(function() {
                adults_value = $("#adults").val();
                kids_value = $("#kids").val();
                var total = parseInt(adults_value) + parseInt(kids_value);

                if (kids_value > kids_capacity) {
                    $("#kids").val(0);
                    $.alert({
                        type: 'red',
                        title: 'Error!',
                        content: "Highest kids capacity exceeded"
                    });
                } else if (total > higher_capacity) {
                    $("#adults").val(0);
                    $("#kids").val(0);
                    $.alert({
                        type: 'red',
                        title: 'Error!',
                        content: "Highest capacity exceeded"
                    });
                }
            });

            $("#room_type").change(function() {
                // if( guest_id !=0){
                load_elements();
                // }
            });

            function load_elements() {
                $('.guest-list-ajax').select2({
                    disabled: 'readonly'
                });
                $("#room_type").attr('disabled', true);
                room_type_id = $("#room_type").children("option:selected").val();

                $.ajax({
                    url: "{{ route('admin.reservation.new.get_room_type_details') }}",
                    data: {
                        room_type_id: room_type_id
                    },
                    success: function(result) {

                        hours = result.hours;
                        base_price = result.base_price;
                        higher_capacity = result.higher_capacity;
                        kids_capacity = result.kids_capacity;

                        if (result.hours == 24) {
                            $('#checkout_div').show();
                        }
                        $('#guests_div').show();
                        $('#checkin_div').show();
                        $('#visitors_div').show()
                    }
                });
            }

            $('#checkin').change(function() {
                if (hours != 24) days_update();
            });

            $('#checkout').change(function() {
                days_update();
            });

            $("#submit").click(function() {
                var table = $("#selecting_table");
                var rows = $("#selecting_table tr").length;
                var room_list = [];
                for (var i = 1; i < rows; i++) {
                    room_list.push($("#selecting_table").find("tr").eq(i).children("td:nth-child(3)")
                        .children().val());
                }

                guest_data();

                if (!guest_list[0]['name'])
                    $.alert({
                        type: 'red',
                        title: 'Error!',
                        content: "Please enter the guest details"
                    });
                else if (room_type_id == 0 || room_type_id == "")
                    $.alert({
                        type: 'red',
                        title: 'Error!',
                        content: "Please select a room type"
                    });
                else if (adults_value == 0 || adults_value == "" || adults_value < 0)
                    $.alert({
                        type: 'red',
                        title: 'Error!',
                        content: "Please enter the number of adults"
                    });
                else if (kids_value < 0)
                    $.alert({
                        type: 'red',
                        title: 'Error!',
                        content: "Please enter a valid number of kids"
                    });
                else if (check_in_date == "")
                    $.alert({
                        type: 'red',
                        title: 'Error!',
                        content: "Please select a check in  date"
                    });
                else if (hours == 24 && check_out_date == "")
                    $.alert({
                        type: 'red',
                        title: 'Error!',
                        content: "Please select a check out  date"
                    });
                else
                    $.ajax({
                        method: "post",
                        url: "{{ route('admin.reservation.new.store') }}",
                        data: {
                            guests: guest_list,
                            room_type_id: room_type_id,
                            adults: adults_value,
                            kids: kids_value,
                            check_in: check_in_date,
                            check_out_date: check_out_date,
                            room_list: room_list,
                            _token: token
                        },
                        success: function(result) {

                            if (result == "success")
                                window.open("{{ route('backend.admin.reservation') }}", "_self");
                            else
                                $.alert({
                                    type: 'red',
                                    title: 'Error!',
                                    content: "Something went wrong!"
                                });
                        }
                    });
            });

            function days_update() {
                check_in_date = $('#checkin').val();
                check_out_date = $('#checkout').val();

                $.ajax({
                    url: "{{ route('admin.reservation.new.get_available_dates') }}",
                    data: {
                        room_type_id: room_type_id,
                        hours: hours,
                        check_in_date: check_in_date,
                        check_out_date: check_out_date
                    },
                    success: function(result) {

                        var total = result[1];
                        result = result[0];

                        if (result == "no_rooms") {
                            $('#submit').hide();
                            $("#selecting_table").hide();
                            $("#price_table").hide();
                            $('#no_rooms').show();
                        } else {
                            $('#no_rooms').hide();
                            var table = $("#selecting_table");
                            table.show();
                            $('#submit').show();
                            $("#selecting_table tr:gt(0)").remove();

                            for (var key in result) {

                                var data = [result[key].from, result[key].to, "", result[key].price];
                                insertTableRow(table, data, key);
                                var row = parseInt(key) + 1;
                                $("#selecting_table").find("tr").eq(row).children("td:nth-child(3)")
                                    .html("<select id='sel" + row + "'></select>");
                                $.each(result[key].rooms, function(i, item) {
                                    $('#sel' + row).append($('<option>', {
                                        value: item,
                                        text: item
                                    }));
                                });
                            }

                            $("#price_table").show();
                            // insertTableRow($("#price_table"), [ "", total ], 0);
                            $("#price_table").find("tr").eq(0).children("td:nth-child(1)").html(
                                "<b>Total</b>");
                            $("#price_table").find("tr").eq(0).children("td:nth-child(2)").html("<b>" +
                                total + "</b>");
                        }
                    }
                });
            }

            $('#checkin').datetimepicker({
                // minView: 2,
                format: 'yyyy-mm-dd hh:ii',
                startDate: new Date((new Date).getTime() - 15*60000),
                // endDate: new Date(),
                weekStart: 1,
                todayBtn: 1,
                todayHighlight: 1,
                showMeridian: 1,
                startView: 2,
                forceParse: 0,
                autoclose: true
            });

            $('#checkout').datetimepicker({
                minView: 2,
                format: 'yyyy-mm-dd',
                startDate: new Date((new Date).getTime() - 15*60000),
                // endDate: new Date(),
                weekStart: 1,
                todayBtn: 1,
                todayHighlight: 1,
                showMeridian: 1,
                startView: 2,
                forceParse: 0,
                autoclose: true
            });

            $('#checkin').datetimepicker().on('changeDate', function(ev) {
                if (hours == 24) {
                    var date = new Date($('#checkin').val());
                    var added_day = date.setDate(date.getDate() + 1);
                    const ye = new Intl.DateTimeFormat('en', {
                        year: 'numeric'
                    }).format(added_day);
                    const mo = new Intl.DateTimeFormat('en', {
                        month: '2-digit'
                    }).format(added_day);
                    const da = new Intl.DateTimeFormat('en', {
                        day: '2-digit'
                    }).format(added_day);
                    added_day = ye + "-" + mo + "-" + da;
                    $('#checkout').datetimepicker('setStartDate', added_day);

                    $('#submit').hide();
                    $("#selecting_table").hide();
                    $("#price_table").hide();
                    $('#no_rooms').hide();

                    $('#checkout').val("");
                    $('#checkout').datetimepicker('show');
                }
            });

            $('#checkout').on('click', function(ev) {
                if ($('#checkin').val() == "") {
                    $('#checkout').datetimepicker('hide');
                    $('#checkin').datetimepicker('show');
                }
            });

            function insertTableRow(table, rowData, index) {
                var newRow = $('<tr/>').insertAfter(table.find('tr').eq(index));
                $(rowData).each(function(colIndex) {
                    newRow.append($('<td/>').text(this));
                });

                return newRow;
            }

            function appendTableRow(table, rowData) {
                //table.find('tr:last').index() also works
                return insertTableRow(table, rowData, -1);
            }

           function guest_data() {

                var $rows = $('#table').find('tr:not(:hidden)');
                var headers = [];
                var data = [];

                // Get the headers (add special header logic here)
                $($rows.shift()).find('th:not(:empty)').each(function() {
                    headers.push($(this).text().toLowerCase());
                });

                // Turn all existing rows into a loopable array
                $rows.each(function() {
                    var $td = $(this).find('td');
                    var h = {};

                    // Use the headers from earlier to name our hash keys
                    headers.forEach(function(header, i) {
                        //alert("i");
                        h[header] = $td.eq(i).children().val();

                        /*
                        if(i==4){
                          h[header] = $td.eq(i).children().val();
                        }
                        */
                    });

                    data.push(h);
                });

                // Output the result
                // $EXPORT.text(JSON.stringify(data));

                guest_list = data;
           }

        });

    </script>


@endsection
