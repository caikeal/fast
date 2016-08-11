<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('throttle');
    }

    /**
     * 查看级别对应的父权限组.
     * 
     * @param Requests\Admin\AffiliationRequest $request
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function getAffiliation(Requests\Admin\AffiliationRequest $request)
    {
        //判断是否到达相应的权限
        if(\Gate::foruser(\Auth::guard('admin')->user())->denies('super')){
            return \Response::json(['invalid'=>'您无权访问！'])->setStatusCode(403);
        }

        $level = $request->input('level');

        $roles = Role::where('level', $level-1)->get();

        return ["data"=>$roles];
    }

    /**
     * 展现角色列表.
     *
     * @return mixed
     */
    public function showRoles()
    {
        $allRoles = Role::with([
            'father' => function ($query) {
                $query->select(['id', 'label']);
            }
        ])->where('level', '!=', 0)->get(['id','name','label','pid','level']);

        return ['data' => $allRoles];
    }

    /**
     * 添加角色.
     *
     * @param Requests\Admin\RoleCreateRequest $request
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function addRoles(Requests\Admin\RoleCreateRequest $request)
    {
        //判断是否到达相应的权限
        if(\Gate::foruser(\Auth::guard('admin')->user())->denies('super')){
            return \Response::json(['invalid'=>'您无权访问！'])->setStatusCode(403);
        }
        $name = $request->input('name');
        $label = $request->input('label');
        $level = $request->input('level');
        $relate = $request->input('relate');

        //判断name英文必须包含(salary/compensate/welfare/system)前缀
        if (!preg_match('/^(salary|compensate|welfare|system){1}[A-Z]([a-z])+/', $name, $match)){
            return \Response::json(['name'=>['格式错误！']])->setStatusCode(422);
        }

        //relate对应的前缀必须和name前缀一致
        $head = $match[1];
        $pInfo = Role::where('id', $relate)->where('level', $level-1)->first();
        if (!$pInfo){
            return \Response::json(['relate'=>['格式错误！']])->setStatusCode(422);
        }
        if(!preg_match('/^(salary|compensate|welfare|system){1}/', $pInfo['name'], $pMatch)){
            $pMatch[0] = '';
        };
        $pHead = $pMatch[0]?$pMatch[0]:'';
        if ($pHead != $head){
            return \Response::json(['name'=>['命名与所选角色不一致！'], 'relate'=>['命名与所选角色不一致！']])->setStatusCode(422);
        }

        $roles = new Role();
        $roles -> name = $name;
        $roles -> label = $label;
        $roles -> level = $level;
        $roles -> pid = $relate;
        $roles->save();

        return ['message'=>'success', 'data'=>['id'=>$roles['id']]];
    }

    /**
     * 更新角色.
     *
     * @param Requests\Admin\RoleUpdateRequest $request
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function updateRoles(Requests\Admin\RoleUpdateRequest $request)
    {
        //判断是否到达相应的权限
        if(\Gate::foruser(\Auth::guard('admin')->user())->denies('super')){
            return \Response::json(['invalid'=>'您无权访问！'])->setStatusCode(403);
        }

        $label = $request->input('label');
        $id = $request->input('id');
        $isExist = Role::where('label', $label)->count();
        $roles = Role::where('id', $id)->first();
        if ($isExist && !$roles) {
            return \Response::json(['invalid'=>'已经存在！'])->setStatusCode(422);
        }

        $roles->label = $label;
        $roles->update();

        return ["msg" => "success"];
    }
}
