<?php

namespace App\Http\Controllers\Admin;

use App\Manager;
use App\Role;
use App\SalaryUpload;
use Carbon\Carbon;
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
    public function show(Request $request, $id)
    {
        $company = $request->input('company');
        $from = $request->input('from');
        $to = $request->input('to');

        //格式化时间
        if ($from && $to){
            $from = Carbon::parse($from)->toDateTimeString();
            $to = Carbon::parse($to)->toDateTimeString();
        }

        //查询上传记录
        $searchCondition = SalaryUpload::with([
            'company'=>function($query){
                $query->select(['id','name']);
            }
        ])
            ->where('manager_id', $id);

        if ($company){
            $searchCondition = $searchCondition->whereHas('company',function($query) use($company){
                $query->where('name','like',"%".$company."%")->select(['id','name','poster']);
            });
        }

        if ($from && $to){
            $searchCondition = $searchCondition->where('created_at', '>', $from);
            $searchCondition = $searchCondition->where('created_at', '<=', $to);
        }
        $allUploads = $searchCondition->orderBy('created_at','desc')->paginate(15);
        $result = $allUploads->toArray();
        $result['company'] = $company;
        $result['from'] = $from;
        $result['to'] = $to;
        return $result;
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
