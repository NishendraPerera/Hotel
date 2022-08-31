<div class="modal fade" id="checkout_model" tabindex="-1" role="dialog" aria-labelledby="checkout_model" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-clock-o"></i>  Real Checkout Time</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="" action="{{route('admin.reservation.new.manual_checkout',$data->id)}}" method="post" >@csrf
                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>Reservation Number</strong></label>
                                    <input class="form-control" readonly value="{{$data->uid}}">
                                </div>
                            </div>
                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>Real Checkout Time</strong></label>
                                    <input class="form-control" name="checkout_time" id="checkout_time" readonly value="{{date('Y-m-d H:i')}}">
                                </div>
                            </div>
                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <hr/>
                                    <button type="reset" class="btn btn-outline-tsk"><i class="fa fa-refresh"></i> Reset</button>
                                    <button type="submit" class="btn btn-tsk"> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>