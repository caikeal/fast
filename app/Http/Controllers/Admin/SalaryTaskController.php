<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Manager;
use App\SalaryTask;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class SalaryTaskController extends Controller
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
        $name=trim($request->input('name'));
        //查询企业名
        $manager_id = \Auth::guard('admin')->user()->id;
        if($name){
            $tasks=SalaryTask::whereHas('company',function($query) use($name){
                $query->where('name','like',"%".$name."%");
            })->with('receiver')->where("manager_id",$manager_id)
            ->orWhere("receive_id",$manager_id)->orWhere("by_id",$manager_id)
            ->orderBy('deal_time','desc')->paginate(15);
        }else {
           $tasks=SalaryTask::with('company')->with('receiver')->orWhere("by_id",$manager_id)
               ->where("manager_id",$manager_id)->orWhere("receive_id",$manager_id)
               ->orderBy('deal_time','desc')->paginate(15);
        }

        //所有企业
        $companys=Company::get();
        //创建的成员
        $ownManagers=Manager::where('pid',$manager_id)->orWhere('id',$manager_id)->get();
        return view('admin.salaryTask',['tasks'=>$tasks,'name'=>$name,'companys'=>$companys,'ownManagers'=>$ownManagers]);
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
    public function store(Requests\Admin\SalaryTaskRequest $request)
    {
        if(Gate::foruser(\Auth::guard('admin')->user())->denies('addTask')){
            return redirect('admin/index');
        }
        $name=$request->input('name');
        $receiver=$request->input('receiver');
        $salaryDay=$request->input('sd');
        $insuranceDay=$request->input('id');
        $memo=$request->input('memo');

        $manager_id=\Auth::guard('admin')->user()->id;
        $tasks=SalaryTask::where("company_id",$name)
        ->where(function($query) use($salaryDay,$insuranceDay){
            $query->where(function($query) use($salaryDay) {
                $query->where("deal_time",strtotime($salaryDay))->where("type",1);
            })->orWhere(function($query) use($insuranceDay){
                $query->where("deal_time",strtotime($insuranceDay))->where("type",2);
            });
        })->first();
        if($tasks){
            $result['ret_num']=111;
            $result['ret_msg']='该日任务已经存在！';
            return response()->json($result);
        }

        if($salaryDay){
            $task=new SalaryTask();
            $task->company_id=$name;
            $task->manager_id=$manager_id;
            $task->receive_id=$receiver;
            $task->memo=$memo;
            $task->type=1;
            $task->deal_time=strtotime($salaryDay);
            $task->salary_day=date("Ym",strtotime($salaryDay));
            $task->save();
        }
        if($insuranceDay){
            $task2=new SalaryTask();
            $task2->company_id=$name;
            $task2->manager_id=$manager_id;
            $task2->receive_id=$receiver;
            $task2->memo=$memo;
            $task2->type=2;
            $task2->deal_time=strtotime($insuranceDay);
            $task2->salary_day=date("Ym",strtotime($insuranceDay));
            $task2->save();
        }
        $result['ret_num']=0;
        $result['ret_msg']='保存成功！';
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
        if(Gate::foruser(\Auth::guard('admin')->user())->denies('editTask')){
            return redirect('admin/index');
        }
        $task=SalaryTask::where("id",$id)->first();
        $task->deal_time=date("Y-m-d",$task->deal_time);
        $result['ret_num']=0;
        $result['ret_msg']='操作成功！';
        $result['val']=$task;
        return response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\Admin\SalaryTaskRequest $request, $tid)
    {
        if(Gate::foruser(\Auth::guard('admin')->user())->denies('editTask')){
            return redirect('admin/index');
        }
        $name=$request->input('name');
        $receiver=$request->input('receiver');
        $salaryDay=$request->input('sd');
        $insuranceDay=$request->input('id');
        $memo=$request->input('memo');

        $manager_id=\Auth::guard('admin')->user()->id;
        $task=SalaryTask::where("id",$tid)->where("company_id",$name)->first();
        if(!$task){
            $result['ret_num']=111;
            $result['ret_msg']='该任务不存在！';
            return response()->json($result);
        }

        if($salaryDay){
            $task->type=1;
            $task->deal_time=strtotime($salaryDay);
            $task->salary_day=date("Ym",strtotime($salaryDay));
        }
        if($insuranceDay){
            $task->type=2;
            $task->deal_time=strtotime($insuranceDay);
            $task->salary_day=date("Ym",strtotime($insuranceDay));
        }
        if($receiver){
            $task->receive_id=$receiver;
            $task->by_id=$manager_id;
        }
        if($memo){
            $task->memo=$memo;
        }
        $task->save();
        $result['ret_num']=0;
        $result['ret_msg']='保存成功！';
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
