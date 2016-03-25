<?php

namespace App\Http\Controllers\Admin;

use App\Manager;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\Admin\AccountRequest $request)
    {
        $name=$request->input('name');
        $account=$request->input('account');
        $password=$request->input('pwd');
        $role=$request->input('role');
        DB::beginTransaction();
        try {
            $manager=new Manager();
            $manager->name=$name;
            $manager->email=$account;
            $manager->password=bcrypt($password);
            $manager->save();
            $allRoles=Role::whereIn('id',$role)->get();
            foreach($allRoles as $itemRole){
                $manager->roles()->save($itemRole);
            }
            $result['ret_num']=0;
            $result['ret_msg']='保存成功！';
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
