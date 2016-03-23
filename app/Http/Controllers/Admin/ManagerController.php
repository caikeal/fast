<?php

namespace App\Http\Controllers\Admin;

use App\Manager;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('throttle');
    }

    public function super(Request $request){
//        if(Gate::denies('super')){
//            return redirect('admin/index');
//        }
        $name=$request->input('name');
        if($name){
            $managers=Manager::whereHas("roles",function($query) use($name){
                $query->where('label','like',"%".$name."%");
            })->orWhere('name','like',"%".$name."%")
                ->orWhere('email','like',"%".$name."%")
                ->paginate(15);
        }else{
            $managers=Manager::with("roles")->paginate(15);
        }
//        dd(Manager::with("roles"));
        return view('admin.super',['managers'=>$managers,'name'=>$name]);
    }


    public function index(){
        return view();
    }
}
