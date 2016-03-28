<?php

namespace App\Http\Controllers\Admin;

use App\Manager;
use App\Role;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class EmployController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('throttle');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Gate::foruser(\Auth::guard('admin')->user())->denies('employ')){
            return redirect('admin/index');
        }

        $name=trim($request->input('name'));
        //不能查询到自己
        $manager_id = \Auth::guard('admin')->user()->id;
        if($name){
            $managers=Manager::whereRaw("find_in_set(id,queryChildren($manager_id))")
                ->with(['tasks' => function ($query) {
                    $query->where('status', 0);
                }])->where('id','!=',$manager_id)->with('leader')
                ->where(function($query) use ($name){
                    $query->where('name','like',"%".$name."%")
                        ->orWhere('email','like',"%".$name."%");
                })->paginate(15);
        }else {
            $managers = Manager::whereRaw("find_in_set(id,queryChildren($manager_id))")
                ->with(['tasks' => function ($query) {
                    $query->where('status', 0);
                }])->where("id", "!=", $manager_id)->with('leader')->paginate(15);
        }
        //下级权限
        $roles=Manager::where("id",$manager_id)->first()->roles()->get();
        $role_arr=[];
        foreach($roles as $role){
            $role_arr[]=$role->id;
        }
        $memberRoles=Role::whereIn('pid',$role_arr)->get();
        return view('admin.employ',['managers'=>$managers,'name'=>$name,'memberRoles'=>$memberRoles]);
    }
}
