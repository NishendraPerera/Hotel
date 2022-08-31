<?php

namespace App\Http\Controllers\Backend\Admin\HotelConfigure;

use App\Http\Helper\MimeCheckRules;
use App\Model\Food;
use App\Model\Room;
use App\Model\RoomType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use Yajra\DataTables\Facades\DataTables;

class FoodController extends Controller
{
    /**
     * @var Room
     */
    private $room;
    /**
     * @var RoomType
     */
    private $roomType;
    /**
     * @var Food
     */
    private $food;

    public  function __construct(Food $food,Room $room,RoomType $roomType)
    {
        $this->room = $room;
        $this->roomType = $roomType;
        $this->food = $food;
    }

    public function index(){
        //$foods = $this->food->get();
        return view('backend.admin.hotel_configure.food.index'/*,compact('foods')*/);
    }

    public function food_list(){
        $data = Food::orderBy('title')->get();

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('price', function($row){
                    $price = number_format($row->price,2);
                    return $price;
                })
                ->addColumn('status', function($row){
                    $row->status ? $btn = "<span class='badge badge-success'>Active</span>" : $btn =  "<span class='badge badge-danger'>Inactive</span>";
                    return $btn;
                })
                ->addColumn('action', function($row){
                    $link = route('backend.admin.food.edit', $row->id);
                    $btn = '<a href="'.$link.'" class="btn btn-outline-tsk"><i class="fa fa-eye"></i>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);                
    }

    public function create(){
        $room_types = $this->roomType->where('status',1)->get();
        return view('backend.admin.hotel_configure.food.create',compact('room_types'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'title'=>'required|max:191|unique:foods',
            'price'=>'required|numeric'
        ]);
        $food = new $this->food;
        // $food->icon = $request->icon;
        $food->title = $request->title;
        $food->price = $request->price;
        $food->status = $request->has('status')?1:0;
        $food->save();
        return redirect()->back()->with('success','Save successful');
    }
    public function edit($id){
        $food = $this->food->findOrFail($id);
        $room_types = $this->roomType->where('status',1)->get();
        return view('backend.admin.hotel_configure.food.edit',compact('food','room_types'));
    }
    public function update(Request $request,$id){
        $this->validate($request,[
            'title'=>'required|max:191|unique:foods,title,'.$id,
            'price'=>'required|numeric',
        ]);
        $food =  $this->food->findOrFail($id);
        // $food->icon = $request->icon;
        $food->title = $request->title;
        $food->price = $request->price;
        $food->status = $request->has('status')?1:0;
        $food->save();
        return redirect()->back()->with('success','Update successful');
    }
    public function delete($id){
        $this->food->findOrFail($id)->delete();
        return redirect()->back()->with('success','Delete successful');
    }
}
