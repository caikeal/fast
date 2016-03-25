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
        $name=trim($request->input('name'));
        if($name){
            $managers=Manager::where('id','!=',\Auth::guard('admin')->user()->id)
                ->where(function($query) use ($name){
                    $query->where('name','like',"%".$name."%")
                        ->orWhere('email','like',"%".$name."%")
                        ->orWhereHas("roles",function($query) use($name){
                            $query->where('label','like',"%".$name."%");
                        });
                })->withTrashed()->paginate(15);
        }else{
            $managers=Manager::with("roles")->where('id','!=',\Auth::guard('admin')->user()->id)->withTrashed()->paginate(15);
        }
        return view('admin.super',['managers'=>$managers,'name'=>$name]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\Admin\ManagerPasswordRequest $request, $id){
        $manager=Manager::where('id',$id)->first();
        $manager->password=bcrypt($request->input('pwd'));
        if($manager->save()){
            $result['ret_num']=0;
            $result['ret_msg']='保存成功！';
        }else{
            $result['ret_num']=110;
            $result['ret_msg']='修改失败！';
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
        $manager=Manager::where("id",$id)->withTrashed()->first();
        if($manager['deleted_at']){
            $manager->restore();
            $result['ret_msg']="停用";
        }else{
            $manager->delete();
            $result['ret_msg']="启用";
        }
        $result['ret_num']=0;
        return response()->json($result);
    }

    public function index(){
        return view();
    }
}
