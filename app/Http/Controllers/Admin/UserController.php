<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
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
    public function index(Requests\Admin\SearchUserRequest $request)
    {
        if(Gate::foruser(\Auth::guard('admin')->user())->denies('super')){
            return redirect('admin/index');
        }
        $phone=$request->input('phone');
        $user=User::with('company')->where('phone',$phone)->first();
        $result['ret_num']=0;
        $result['ret_msg']=$user;
        return response()->json($result);
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
        if(Gate::foruser(\Auth::guard('admin')->user())->denies('super')){
            return redirect('admin/index');
        }
        $user=User::where('id',$id)->where('phone',$request->input('phone'))->first();
        $user->phone='';
        $user->is_first=0;
        $user->remember_token='';
        if($user->save()){
            $result['ret_num']=0;
            $result['ret_msg']='初始化成功！';
        }else{
            $result['ret_num']=110;
            $result['ret_msg']='初始化失败！';
        }
        return response()->json($result);
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
