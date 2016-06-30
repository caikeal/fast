<?php

namespace App\Http\Controllers\Admin;

use App\Manager;
use App\Role;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UnderlingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('throttle');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name=trim($request->input('name'));
        //不能查询到自己
        $manager_id = \Auth::guard('admin')->user()->id;
        if($name){
            $managers=Manager::whereRaw("find_in_set(id,queryChildren($manager_id))")
                ->with(['tasks' => function ($query) {
                    $query->groupBy('company_id');
                }])->where('id','!=',$manager_id)->with('leader')
                ->where(function($query) use ($name){
                    $query->where('name','like',"%".$name."%")
                        ->orWhere('email','like',"%".$name."%");
                })->paginate(15);
        }else {
            $managers = Manager::whereRaw("find_in_set(id,queryChildren($manager_id))")
                ->with(['tasks' => function ($query) {
                    $query->groupBy('company_id');
                }])->where("id", "!=", $manager_id)->with('leader')->paginate(15);
        }
        //下级权限
        $roles=Manager::where("id",$manager_id)->first()->roles()->get();
        $role_arr=[];
        foreach($roles as $role){
            $role_arr[]=$role->id;
        }
        $memberRoles=Role::whereIn('pid',$role_arr)->get();
        return view('admin.underling',['managers'=>$managers,'name'=>$name,'memberRoles'=>$memberRoles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
