<?php

namespace App\Http\Controllers\Admin;

use App\Manager;
use App\Role;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('throttle');
    }

    /**
     * 管理页面.
     *
     * @return mixed
     */
    public function super(){
        if(Gate::foruser(\Auth::guard('admin')->user())->denies('super')){
            return redirect('admin/index');
        }
        $memberRoles=Role::where('level',1)->get();
        return view('admin.super',['memberRoles'=>$memberRoles]);
    }

    /**
     * 管理列表接口.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request){
        if(Gate::foruser(\Auth::guard('admin')->user())->denies('super')){
            return \Response::json(['invalid'=>'您无权访问！'])->setStatusCode(403);
        }
        $name=trim($request->input('name'));

        //不能查询到自己，和超管(id=1)
        if($name){
            $managers=Manager::with("roles")->where('id','!=',\Auth::guard('admin')->user()->id)
                ->where('id',"!=",1)
                ->where(function($query) use ($name){
                    $query->where('name','like',"%".$name."%")
                        ->orWhere('email','like',"%".$name."%")
                        ->orWhereHas("roles",function($query) use($name){
                            $query->where('label','like',"%".$name."%");
                        });
                })->select(['id','name','phone','email','pid', 'updated_at', 'deleted_at'])->withTrashed()->paginate(15);
        }else{
            $managers=Manager::with("roles")->where('id','!=',\Auth::guard('admin')->user()->id)
                ->where('id','!=',1)->select(['id','name','phone','email','pid', 'updated_at', 'deleted_at'])->withTrashed()->paginate(15);
        }

        return $managers;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\Admin\ManagerRequest  $request
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\Admin\ManagerRequest $request, $id){
        $phone = $request->input('phone');
        $email = $request->input('email');
        $user_id = $request->get('user_id');

        //重新登录参数
        $needReLogin = 0;
        $url = '';

        if ($id != Auth::guard('admin')->user()->id && $user_id != $id){
            return response()->json(['invalid'=>'您无权限！'])->setStatusCode(422);
        }

        //更新用户信息
        $manager = Manager::findOrFail($id);
        if ($phone) {
            $manager->phone = $phone;
        }

        if ($email) {
            //判断是否要重新登录
            if ($manager->email != $email){
                $needReLogin = 1;
            }
            $manager->email = $email;
        }

        //用户已完成首次登录
        $manager->is_first = 1;

        //返回成功
        if ($manager->update()){
            //返回要需要重新登录的信息
            if ($needReLogin){
                Auth::guard('admin')->logout();
                $url = url('admin/login');
            }

            $result['ret_num'] = 0;
            $result['ret_msg'] = '保存成功！';
            $result['reLogin'] = $needReLogin;
            $result['reUrl'] = $url;
        }else{
            $result['ret_num'] = 230;
            $result['ret_msg'] = '保存失败！';
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
        if(Gate::foruser(\Auth::guard('admin')->user())->denies('super')){
            return redirect('admin/index');
        }
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

    /**
     * 针对超级管理员重新设置后台用户密码.
     *
     * @param Requests\Admin\ManagerPasswordRequest $request
     * @param $id
     * @return mixed
     */
    public function reset(Requests\Admin\ManagerPasswordRequest $request, $id){
        if(Gate::foruser(\Auth::guard('admin')->user())->denies('super')){
            return redirect('admin/index');
        }

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
}
