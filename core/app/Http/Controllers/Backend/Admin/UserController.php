<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Helper\MimeCheckRules;
use App\Model\Admin;
// use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * @var $user
     */
    private $user;

    public function __construct(Admin $user)
    {
        $this->user = $user;
    }
    public function index(){

        $users = $this->user->whereRole(1)->paginate(15);
        return view('backend.admin.user.index',compact('users'));

        //$users = $this->user->whereRole(1)->paginate(15);
        return view('backend.admin.user.index'/*,compact('users')*/);
    }

    public function user_list(){
        $data = Admin::get();

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function($row){
                    $link = route('backend.admin.user.view', $row->id);
                    $btn = '<a href="'.$link.'">'.$row->full_name.'</a>';
                    return $btn;
                })
                ->addColumn('status', function($row){
                    $row->status ? $btn = "<span class='badge badge-success'>Active</span>" : $btn =  "<span class='badge badge-danger'>Inactive</span>";
                    return $btn;
                })
                ->addColumn('action', function($row){
                    $link = route('backend.admin.user.view', $row->id);
                    $btn = '<a href="'.$link.'" class="btn btn-outline-tsk"><i class="fa fa-eye"></i>';
                    return $btn;
                })
                ->rawColumns(['full_name','status','action'])
                ->make(true);
    }

    public function create(){
        return view('backend.admin.user.create');
    }
    public function store(Request $request){
        $this->validate($request,[
            'username'=>'required|max:191|string|unique:users',
            'first_name'=>'required|max:191|string',
            'last_name'=>'nullable|max:191|string',
            'phone'=>'nullable|max:191|string',
            'email'=>'nullable|max:191|string',
            'address'=>'nullable|string',
            'sex'=>'required|string',
            'role'=>'required|integer',
            'password'=>'required|string',
            'picture'=>['nullable',new MimeCheckRules(['png']),'max:2048','image']
        ]);
        $users = new $this->user;
        $users->username = $request->username;
        $users->first_name = $request->first_name;
        $users->last_name = $request->last_name;
        $users->phone = $request->phone;
        $users->email = $request->email;
        $users->address = $request->address;
        $users->sex = $request->sex;
        $users->role = $request->role;
        if($request->has('picture')){
            $path_pic = 'assets/backend/image/user/pic/';
            $users->picture = 'pic_'.time().'.png';
            Image::make($request->picture)->save($path_pic.$users->picture);
        }
        $users->password = bcrypt($request->password);
        $users->status = $request->has('status')?1:0;
        $users->save();
        return redirect()->back()->with('success','User save successful');
    }
    public function view($id){
        $user = $this->user->findOrFail($id);
        return view('backend.admin.user.view',compact('user'));
    }

    public function update(Request $request,$id){
        $this->validate($request,[
            'first_name'=>'nullable|max:191|string',
            'last_name'=>'nullable|max:191|string',
            'phone'=>'nullable|max:191|string',
            'email'=>'nullable|max:191|string',
            'address'=>'nullable|string',
            'sex'=>'required|string',
            'role'=>'required|integer',
            'password'=>'nullable|string',
            'picture'=>['nullable',new MimeCheckRules(['png']),'max:2048','image'],
        ]);
        $users = $this->user->findOrFail($id);
        $users->first_name = $request->first_name;
        $users->last_name = $request->last_name;
        $users->phone = $request->phone;
        $users->email = $request->email;
        $users->address = $request->address;
        $users->sex = $request->sex;
        $users->role = $request->role;
        if($request->has('picture')){
            $path_pic = 'assets/backend/image/user/pic/';
            @unlink($path_pic.$users->picture);
            $users->picture = 'pic_'.time().'.png';
            Image::make($request->picture)->save($path_pic.$users->picture);
        }
        if($request->password){
            $users->password = bcrypt($request->password);
        }

        $users->status = $request->has('status')?1:0;
        $users->save();
        return redirect()->back()->with('success','User update successful');
    }
}
