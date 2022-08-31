<div class="modal fade" id="add_discount" tabindex="-1" role="dialog" aria-labelledby="add_discount" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Add Discount</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="" action="{{route('admin.reservation.new.discount',$data->id)}}" method="post" >@csrf
                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>Reservation Number</strong></label>
                                    <input class="form-control" readonly value="{{$data->uid}}">
                                </div>
                            </div>

                            <div class="form-row justify-content-center">
                                <div class="form-group col-md-6">
                                    <label><strong>Discount Type</strong> <small class="text-danger">*</small></label>
                                    <select class="form-control form-control-lg" name="discount_type" id="discount_type" required>
                                        <option value="PERCENTAGE" selected>Percentage</option>
                                        <option value="FIXED">Fixed</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label><strong>Discount Value</strong> <small class="text-danger">*</small></label>
                                    <input type="number" class="form-control form-control-lg" name="discount_value" id="discount_value" placeholder="Value" value="{{old('value',0)}}" min="1" required>
                                </div>
                            </div>

                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>Current Paybale</strong></label>
                                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{number_format($reservation->payable()-$reservation->payment->sum('amount'),2)}}">
                                </div>
                            </div>

                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>New Payable</strong></label>
                                    <input type="text" readonly class="form-control-plaintext" id="new_payable" value="{{number_format($reservation->payable()-$reservation->payment->sum('amount'),2)}}">
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
