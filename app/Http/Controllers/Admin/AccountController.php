<?php

namespace App\Http\Controllers\Admin;

use App\Manager;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

/**
 * 账户管理，用于新建账户、修改账户密码.
 *
 * Class AccountController
 */
class AccountController extends Controller
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
    public function index()
    {
        //
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
     * @param  Requests\Admin\AccountRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\Admin\AccountRequest $request)
    {
        if(Gate::foruser(\Auth::guard('admin')->user())->denies('super') && Gate::foruser(\Auth::guard('admin')->user())->denies('addEmploy')){
            return redirect('admin/index');
        }
        $name=$request->input('name');
        $account=$request->input('account');
        $password=$request->input('pwd');
        $role=$request->input('role');
        DB::beginTransaction();
        try {
            $manager=new Manager();
            $manager->name=$name;
            $manager->email=$account;
            $manager->pid=\Auth::guard('admin')->user()->id;
            $manager->password=bcrypt($password);
            $manager->save();

            $allRoles=Role::whereIn('id',$role)->get();
            foreach($allRoles as $itemRole){
                $manager->roles()->save($itemRole);
            }
            $result['ret_num']=0;
            $result['ret_msg']='保存成功！';
            $result['data']=$manager::with('roles')->find($manager->id);
            DB::commit();
        } catch (Exception $e){
            $result['ret_num']=110;
            $result['ret_msg']='保存失败，请重新再试！';
            DB::rollback();
        }
        return response()->json($result);
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
     * @param  Requests\Admin\ManagerPasswordResetRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\Admin\ManagerPasswordResetRequest $request, $id)
    {
        $oldPwd = $request->input('old_pwd');
        $pwd = $request->input('pwd');
        $user_id = $request->get('user_id');

        //重新登录参数
        $needReLogin = 1;
        $url = '';

        if ($id != Auth::guard('admin')->user()->id && $user_id != $id){
            return response()->json(['invalid'=>'您无权限！'])->setStatusCode(422);
        }

        //密码验证认证
        $manager = Manager::find($id);
        if (!Hash::check($oldPwd, $manager['password'])){
            return response()->json(['old_pwd'=>['旧密码错误！']])->setStatusCode(422);
        }

        //重置密码
        $manager->password = bcrypt($pwd);
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
        //
    }
}
