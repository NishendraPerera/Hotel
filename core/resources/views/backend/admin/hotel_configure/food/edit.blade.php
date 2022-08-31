@extends('backend.master')
@section('title',"Edit Food & Beverage")
@section('content')
    <div class="card">
        <div class="card-header bg-white">
            <h2>Edit Food & Beverage
                <a class="btn btn-tsk float-right" href="{{route('backend.admin.food')}}"><i class="fa fa-list"></i> Food & Beverage List</a>

            </h2>
        </div>
        <div class="card-body">
            <form action="{{route('backend.admin.food.update',$food->id)}}" method="post" enctype="multipart/form-data">@csrf
                <div class="form-row justify-content-center">
                    <div class="form-group col-md">
                        <label><strong>Title</strong> <small class="text-danger">*</small></label>
                        <input type="text" class="form-control form-control-lg" name="title" placeholder="Title" value="{{$food->title}}" required>
                    </div>
                    <div class="form-group col-md ">
                        <label><strong>Price</strong> <small class="text-danger">*</small></label>
                        <input type="text" class="form-control  form-control-lg" name="price" placeholder="Price" value="{{$food->price}}" required>
                    </div>
                </div>
                <div class="form-row">
                    {{-- <div class="form-group col-md-6 ">
                        <label><strong>Icon</strong> <small class="text-danger">*</small></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-{{$food->icon}}"></i> </div>
                            </div>
                            <div class="input-group-prepend">
                                <div class="input-group-text">fa fa-</div>
                            </div>
                            <input type="text" class="form-control form-control-lg" name="icon" placeholder="Icon" value="{{$food->icon}}" required>
                        </div>
                    </div> --}}
                    <div class="form-group col-md-6">
                        <label for="inputAddress2" class=" mr-5">Status</label>
                        <input id="status" {{$food->status?'checked':''}} data-width="100%" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" name="status">
                    </div>
                </div>
                <div class="form-row justify-content-center">
                    <div class="form-group col-sm-12">
                        <hr/>
                        <button type="submit" class="btn btn-lg mt-4 btn-tsk btn-block mt-5"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript">
        bkLib.onDomLoaded(function() {
            new nicEditor({
                iconsPath : '{{asset('assets/plugin/niceditor/nicEditorIcons.gif')}}',
                fullPanel : true
            }).panelInstance('long_desc');
        });
    </script>
@endsection