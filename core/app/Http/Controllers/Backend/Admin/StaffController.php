<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Helper\MimeCheckRules;
use App\Model\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller
{
    /**
     * @var $staff
     */
    private $staff;

    public function __construct(Staff $staff)
    {
        $this->staff = $staff;
    }
    public function index(){
        $staffs = $this->staff->paginate(15);
        return view('backend.admin.staff.index',compact('staffs'));

        // $staffs = $this->staff->paginate(15);
        return view('backend.admin.staff.index'/*,compact('staffs')*/);
    }

    public function staff_list(){
        $data = Staff::latest()->get();

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function($row){
                    $link = route('backend.admin.staff.view', $row->id);
                    $btn = '<a href="'.$link.'">'.$row->full_name.'</a>';
                    return $btn;
                })
                ->addColumn('status', function($row){
                    $row->status ? $btn = "<span class='badge badge-success'>Active</span>" : $btn =  "<span class='badge badge-danger'>Inactive</span>";
                    return $btn;
                })
                ->addColumn('action', function($row){
                    $link = route('backend.admin.staff.view', $row->id);
                    $btn = '<a href="'.$link.'" class="btn btn-outline-tsk"><i class="fa fa-eye"></i>';
                    return $btn;
                })
                ->rawColumns(['full_name','status','action'])
                ->make(true);
    }

    public function create(){
        return view('backend.admin.staff.create');
    }
    public function store(Request $request){
        $this->validate($request,[
            'first_name'=>'required|max:191|string',
            'last_name'=>'nullable|max:191|string',
            'phone'=>'nullable|max:191|string',
            'address'=>'nullable|string',
            'sex'=>'required|string',
            'nic'=>'required|string',
            'picture'=>['nullable',new MimeCheckRules(['png', 'jpg', 'jpeg']),'max:10240','image']
        ]);
        $staffs = new $this->staff;
        $staffs->first_name = $request->first_name;
        $staffs->last_name = $request->last_name;
        $staffs->phone = $request->phone;
        $staffs->address = $request->address;
        $staffs->sex = $request->sex;
        $staffs->nic = $request->nic;
        if($request->has('picture')){
            $path_pic = 'assets/backend/image/staff/pic/';
            $staffs->picture = 'pic_'.time().'.png';
            Image::make($request->picture)->save($path_pic.$staffs->picture);
        }
        $staffs->status = $request->has('status')?1:0;
        $staffs->save();
        return redirect()->back()->with('success','Staff save successful');
    }
    public function view($id){
        $staff = $this->staff->findOrFail($id);
        return view('backend.admin.staff.view',compact('staff'));
    }

    public function update(Request $request,$id){
        $this->validate($request,[
            'first_name'=>'nullable|max:191|string',
            'last_name'=>'nullable|max:191|string',
            'phone'=>'nullable|max:191|string',
            'address'=>'nullable|string',
            'sex'=>'required|string',
            'nic'=>'required|string',
            'picture'=>['nullable',new MimeCheckRules(['png']),'max:2048','image'],
        ]);
        $staffs = $this->staff->findOrFail($id);
        $staffs->first_name = $request->first_name;
        $staffs->last_name = $request->last_name;
        $staffs->phone = $request->phone;
        $staffs->address = $request->address;
        $staffs->sex = $request->sex;
        $staffs->nic = $request->nic;
        if($request->has('picture')){
            $path_pic = 'assets/backend/image/staff/pic/';
            @unlink($path_pic.$staffs->picture);
            $staffs->picture = 'pic_'.time().'.png';
            Image::make($request->picture)->save($path_pic.$staffs->picture);
        }

        $staffs->status = $request->has('status')?1:0;
        $staffs->save();
        return redirect()->back()->with('success','Staff update successful');
    }
}
