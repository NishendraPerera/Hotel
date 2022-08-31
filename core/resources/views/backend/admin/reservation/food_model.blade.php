<div class="modal fade" id="add_food" tabindex="-1" role="dialog" aria-labelledby="add_food" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cutlery"></i>  Food & Beverage</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="" action="{{route('backend.admin.reservation.add_food',$data->id)}}" method="post" >@csrf
                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>Reservation Number</strong></label>
                                    <input class="form-control" readonly value="{{$data->uid}}">
                                </div>
                            </div>
                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>Date</strong></label>
                                    <input class="form-control" name="date" id="food_date" readonly value="{{date('Y-m-d H:i')}}">
                                </div>
                            </div>
                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>Food/ Beverage</strong></label>
                                   <select class="js-example-basic-single form-control" name="food">
                                        <option value="">Select Food/ Beverage</option>
                                        @foreach($foods as $food)
                                            <option value="{{$food->id}}">{{$food->title}} ( {{ number_format($food->price,2)}} {{general_setting()->cur}} )</option>
                                        @endforeach
                                   </select>
                                </div>
                            </div>
                            {{-- <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>Food/ Beverage</strong> <small class="text-danger">*</small></label>
                                    <select class="select2 food-list-ajax form-control form-control-lg"></select>
                                </div>
                            </div> --}}

                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>Description</strong></label>
                                    <input class="form-control" name="description" {{-- readonly --}} value="">
                                </div>
                            </div>
                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>Staff</strong></label>
                                   <select class="form-control" name="staff">
                                       <option value="">Select a Staff</option>
                                       @foreach($staffs as $staff)
                                       <option value="{{$staff->id}}">{{$staff->full_name}}</option>
                                           @endforeach
                                   </select>
                                </div>
                            </div>
                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>Quantity</strong></label>
                                    <input class="form-control" name="qty" value="" placeholder="0" required>
                                </div>
                            </div>
                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <hr/>
                                    <button type="reset" class="btn btn-outline-tsk"><i class="fa fa-refresh"></i> Reset</button>
                                    <button type="submit" class="btn btn-tsk"><i class="fa fa-money"></i> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>