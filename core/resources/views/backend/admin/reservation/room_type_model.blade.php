<div class="modal fade" id="room_type" tabindex="-1" role="dialog" aria-labelledby="room_type" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Change Room Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="" action="{{route('admin.reservation.new.room_type_change',$data->id)}}" method="post" >@csrf
                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>Reservation Number</strong></label>
                                    <input class="form-control" readonly value="{{$data->uid}}">
                                </div>
                            </div>
                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>Current Reservation Type</strong></label>
                                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $reservation->roomType->title }}">
                                </div>
                            </div>

                            @php $room_text = explode('-', $reservation->roomType->title)[0]; @endphp

                            @php $room_types = \App\Model\RoomType::where('title', 'like', $room_text.'%' )->where('id', '!=', $reservation->roomType->id)->orderBy('hours')->get();  @endphp

                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>New Reservation Type</strong></label>
                                    <select id="room_type" class="form-control form-control-lg" name="new_room_type">
                                        <option value="0">Select</option>
                                        @foreach($room_types as $room_type)
                                            <option value="{{$room_type->id}}">{{$room_type->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- @php $room_types = \App\Model\RoomType::where('title', 'like', $room_text.'%' )->where('hours', '>', $reservation->roomType->hours)->get();  @endphp

                            <div class="form-row justify-content-center">
                                <div class="form-group col-sm-12">
                                    <label><strong>New Reservation Type</strong></label>
                                    <select id="room_type" class="form-control form-control-lg" name="new_room_type">
                                        <option value="0">Select</option>
                                        @foreach($room_types as $room_type)
                                            <option value="{{$room_type->id}}">{{$room_type->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}



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