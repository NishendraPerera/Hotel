@extends('backend.master')
@section('title',"User")
@section('style')
    <style>
        .vip {
            position: absolute;
            top:0;
            right: 0;
            background: green;
            font-weight: bold;
            padding: 5px 10px 5px 30px;
            color: white;
            border-radius:0 0 0 30px;
        }
    </style>
    @endsection
@section('content')

    <div class="card">
        <div class="card-header bg-white">
            <h2>User
                <a class="btn btn-tsk float-right" href="{{route('backend.admin.user')}}"><i class="fa fa-list"></i> User List</a>

            </h2>
        </div>
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-md-3">
                    <div class="tile">
                        <div class="card">
                            <div class="card-body text-center bg-tsk-o-1">
                                <div class="img">
                                    <img src="{{$user->picture_path()}}" id="preview_img" data-oldimg="" class="img-thumbnail" style="max-height: 200px;border-radius: 50%" >
                                </div>
                                <dl class="row">
                                    <dt class="col-md-6 text-md-right">User Name : </dt>
                                    <dd class="col-md-6 text-md-left">{{$user->username}}</dd>
                                </dl>
                                <dl class="row">
                                    <dt class="col-md-6 text-md-right">Name : </dt>
                                    <dd class="col-md-6 text-md-left">{{$user->full_name}}</dd>
                                </dl>
                                <dl class="row">
                                    <dt class="col-md-6 text-md-right">Role : </dt>
                                    <dd class="col-md-6 text-md-left">{{$user->role()}}</dd>
                                </dl>
                                <dl class="row">
                                    <dt class="col-md-6 text-md-right">Email : </dt>
                                    <dd class="col-md-6 text-md-left">{{$user->email}}</dd>
                                </dl>
                                <dl class="row">
                                    <dt class="col-md-6 text-md-right">Phone : </dt>
                                    <dd class="col-md-6 text-md-left">{{$user->phone}}</dd>
                                </dl>
                                <dl class="row">
                                    <dt class="col-md-6 text-md-right">Sex : </dt>
                                    <dd class="col-md-6 text-md-left">{{$user->sex()}}</dd>
                                </dl>


                                <dl class="row">
                                    <dt class="col-md-6 text-md-right">Status : </dt>
                                    <dd class="col-md-6 text-md-left"><span class="badge {{$user->status?'badge-success':'badge-danger'}}">{{$user->status?'ACTIVE':'INACTIVE'}}</span></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9 ">

                    <div class="tile">
                        <div class="tile-body">

                            <form action="{{route('backend.admin.user.update',$user->id)}}" method="post" enctype="multipart/form-data">@csrf
                                <div class="form-row justify-content-center">

                                    <div class="form-group col-md-4">
                                        <label><strong>First Name</strong> <small class="text-danger">*</small></label>
                                        <input type="text" class="form-control form-control-lg" name="first_name" placeholder="First Name" value="{{$user->first_name}}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label><strong>Last Name</strong> </label>
                                        <input type="text" class="form-control form-control-lg" name="last_name" placeholder="Last Name" value="{{$user->last_name}}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label><strong>Email</strong> </label>
                                        <input type="email" class="form-control form-control-lg" name="email" placeholder="email" value="{{$user->email}}">
                                    </div>

                                </div>
                                <div class="form-row justify-content-center">
                                    <div class="form-group col-md-4">
                                        <label><strong>Password</strong> </label>
                                        <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" >
                                        <label><strong>Phone</strong> </label>
                                        <input type="text" class="form-control form-control-lg" name="phone" placeholder="Phone" value="{{$user->phone}}">
                                        <label><strong>Sex</strong> <small class="text-danger">*</small></label>
                                        <select  class="form-control form-control-lg" name="sex" >
                                            <option value="M" {{$user->sex==='M'?'selected':''}}>Male</option>
                                            <option value="F" {{$user->sex==='F'?'selected':''}}>Female</option>
                                            <option value="O" {{$user->sex==='O'?'selected':''}}>Other</option>
                                        </select>
                                        <label><strong>Role</strong> <small class="text-danger">*</small></label>
                                        <select  class="form-control form-control-lg" name="role" >
                                            <option value="0" {{$user->role=='0'?'selected':''}}>Admin</option>
                                            <option value="1" {{$user->role=='1'?'selected':''}}>Staff</option>
                                        </select>
                                        <label><strong>Image</strong></label>
                                        <input type="file" class="form-control form-control-lg" name="picture">
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label><strong>Address</strong></label>
                                        <textarea  class="form-control form-control-lg" rows="8" name="address">{{$user->address}}</textarea>
                                        <label for="status" class=" mt-3">Status</label>
                                        <input id="status" {{$user->status?'checked':''}} type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" name="status">
                                    </div>

                                </div>
                                <div class="form-row justify-content-center">
                                    <div class="form-group col-sm-12">
                                        <hr/>
                                        <button type="submit" class="btn btn-block btn-tsk"><i class="fa fa-save"></i> Update</button>
                                    </div>
                                </div>


                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#dob').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'yyyy/mm/dd',
                footer: true, modal: true
            });
        });
    </script>
@endsection