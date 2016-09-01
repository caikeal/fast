<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Fast\Service\AutoTask\AutoTask;
use App\Fast\Service\News\NewsInfo;
use App\Fast\Service\Task\Task;
use App\Manager;
use App\SalaryTask;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class SalaryTaskController extends Controller
{
    protected $taskNews;
    protected $autoTask;
    protected $task;

    public function __construct(NewsInfo $taskNews, AutoTask $autoTask, Task $task)
    {
        $this->task = $task;
        $this->taskNews = $taskNews;
        $this->autoTask = $autoTask;
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
            })
                ->with(['receiver'=>function($query){
                $query->withTrashed();
            }])
                ->where(function($query) use($manager_id){
                $query->where("manager_id",$manager_id)
                    ->orWhere("receive_id",$manager_id)->orWhere("by_id",$manager_id);
            })->orderBy('deal_time','desc')->paginate(15);
        }else {
           $tasks=SalaryTask::with(['company', 'receiver'=>function($query){
               $query->withTrashed();
           }])->where("by_id",$manager_id)
               ->orWhere("manager_id",$manager_id)->orWhere("receive_id",$manager_id)
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
     * @param  Requests\Admin\SalaryTaskRequest  $request
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
        /*
        $tasks=SalaryTask::where("company_id",$name)
        ->where(function($query) use($salaryDay,$insuranceDay){
            $query->where(function($query) use($salaryDay) {
                $query->where("salary_day",date("Ym",strtotime($salaryDay)))->where("type",1);
            })->orWhere(function($query) use($insuranceDay){
                $query->where("salary_day",date("Ym",strtotime($insuranceDay)))->where("type",2);
            });
        })->first();
        if($tasks){
            $result['ret_num']=111;
            $result['ret_msg']='该日任务已经存在！';
            return response()->json($result);
        }
        */
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

            //发送派发任务消息
            $content="管理员".\Auth::guard('admin')->user()->name."给您分配了1个薪资任务，快去看看";
            $this->taskNews->storeNews($manager_id,$receiver,3,$task->id,$content);

            //创建自动任务
            $this->autoTask->storeAutoTask($manager_id, $receiver, 0, $name, 1, strtotime($salaryDay), $memo);
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

            //发送派发任务消息
            $content="管理员".\Auth::guard('admin')->user()->name."给您分配了1个社保任务，快去看看";
            $this->taskNews->storeNews($manager_id,$receiver,4,$task2->id,$content);

            //创建自动任务
            $this->autoTask->storeAutoTask($manager_id, $receiver, Null, $name, 2, strtotime($salaryDay), $memo);
        }
        
        if($salaryDay && $insuranceDay){
            $data=SalaryTask::with('company')->with('receiver')->where("company_id",$name)
                ->where(function($query) use($task,$task2){
                    $query->where(function($query) use($task) {
                        $query->where("id",$task['id']);
                    })->orWhere(function($query) use($task2){
                        $query->where("id",$task2['id']);
                    });
                })->get()->toArray();
        }elseif($salaryDay && !$insuranceDay){
            $data=SalaryTask::with('company')->with('receiver')
                ->where("company_id",$name)->where("id",$task['id'])
                ->get()->toArray();
        }else{
            $data=SalaryTask::with('company')->with('receiver')
                ->where("company_id",$name)->where("id",$task2['id'])
                ->get()->toArray();
        }

        foreach ($data as $k=>$v){
            if ($v['deal_time']){
                $data[$k]['deal_time'] = date("Y-m-d", $v['deal_time']);
            }
            if($v['company']['poster']){
                $data[$k]['company']['poster']=env('APP_URL')."/".$v['company']['poster'];
            }
        }
        $result['ret_num']=0;
        $result['ret_msg']='保存成功！';
        $result['data']=$data;
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
     * @param  Requests\Admin\SalaryTaskRequest  $request
     * @param  int  $tid
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
        if($task['status']!=0){
            $result['ret_num']=121;
            $result['ret_msg']='该任务正在处理中！';
            return response()->json($result);
        }
        if($task['type']==1&&$insuranceDay){
            $result['ret_num']=122;
            $result['ret_msg']='该任务为薪资任务，社保任务请新建！';
            return response()->json($result);
        }
        if($task['type']==2&&$salaryDay){
            $result['ret_num']=132;
            $result['ret_msg']='该任务为社保任务，薪资任务请新建！';
            return response()->json($result);
        }
        if ($task['receive_id']==$receiver){
            $result['ret_num']=135;
            $result['ret_msg']='该任务已经属于该用户！';
            return response()->json($result);
        }

        //获取原参数
        $old = [];
        $old['creator'] = $task['manager_id'];
        $old['receiver'] = $task['receive_id'];
        $old['by'] = $task['by_id'];
        $old['company_id'] = $task['company_id'];
        $old['type'] = $task['type'];

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
        $task->update();

        //分配任务信息提示
        $taskName = '';
        $taskType = 0;
        if ($task->type == 1){
            $taskName = '薪资';
            $taskType = 3;
        }elseif($task->type == 2){
            $taskName = '社保';
            $taskType = 4;
        }

        //发送提醒
        $content="管理员".\Auth::guard('admin')->user()->name."给您分配了1个".$taskName."任务，快去看看";
        $this->taskNews->storeNews($manager_id,$receiver,$taskType,$task->id,$content);

        //修改自动任务
        $this->autoTask->changeAutoTask($old, $task['receive_id'], $task['by_id'], $task['deal_time'], $task['memo']);
        
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
        //检查删除权限
        if(Gate::foruser(\Auth::guard('admin')->user())->denies('deleteTask')){
            return redirect('admin/index');
        }
        $tasks = SalaryTask::find($id);

        if ($tasks['status'] !== 0){
            $result['ret_num']=33;
            $result['ret_msg']='任务已开始暂无法删除！';
            return $result;
        }
        \DB::beginTransaction();
        try{
            //删除薪资、社保任务
            $this->task->deleteTask($id);
            //删除定时任务
            $this->autoTask->deleteAutoTask($tasks['manager_id'], $tasks['receive_id'], $tasks['by_id'], $tasks['company_id'], $tasks['type']);
            \DB::commit();

            $result['ret_num']=0;
            $result['ret_msg']='操作成功！';

            return $result;
        }catch (\Exception $e){
            \DB::rollBack();
            $result['ret_num']=44;
            $result['ret_msg']='修改失败！';

            return $result;
        }

    }
}
