<?php

namespace App\Http\Controllers;

use App\CompensationDetail;
use App\Events\UserLog;
use App\ModuleStatistics;
use App\SalaryCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CompensationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('binding');
    }

    public function index(Request $request)
    {
        $user = \Auth::user()->id;

        //统计模块访问量
        $moduleData = new ModuleStatistics();
        $moduleData->user_id = $user;
        $moduleData->ip = $request->ip();
        $moduleData->module = 'Compensation';
        \Event::fire(new UserLog($moduleData));

        return view('home.compensation');
    }

    public function specific(Request $request)
    {
        $time = $request->get('time');
        if (!$time){
            return redirect('compensation/index');
        }
        return view('home.compensationDetail',['time'=>$time]);
    }

    public function detail(Request $request)
    {
        $salary_day=$request->input('time');
        $type=$request->input('type');
        if(!$salary_day||!is_numeric($salary_day)||$type!=3){
            return response()->json(["status"=>0],200);
        }

        $user_id=\Auth::user()->id;
        $detail=CompensationDetail::where("compensation_day","=",$salary_day)
            ->where("user_id","=",$user_id)->where('type',$type)->first();
        if(!$detail){
            return response()->json(["status"=>0],200);
        }
        $cats=$detail->baseCategory()->orderBy("place","asc")->get();
        $catsName=array();
        foreach($cats as $k=>$v){
            $catsName[]=SalaryCategory::where("id","=",$v['category_id'])->first();
        }
        //组织数据
        $data=array();
        $tpl_detail=array();
        $flag_id=0;
        foreach($catsName as $kk=>$vv){
            if($vv['level']==1){
                $flag_id=$vv['id'];
                $data[]=array(
                    'category'=>$vv['name'],
                    'cid'=>$vv['id'],
                );
            }else{
                $tpl_detail[$flag_id][]=array(
                    "name"=>$vv['name'],
                    "sid"=>$vv['id'],
                );
            }
        }
        $count_arr=0;
        $wages=explode("||",$detail->wages);
        foreach($tpl_detail as $kt=>$vt){
            foreach($vt as $kd=>$vd){
                $tpl_detail[$kt][$kd]['v']=$wages[$count_arr]?$wages[$count_arr]:"";
                $count_arr++;
            }
        }
        foreach($data as $kkd=>$vvd){
            $data[$kkd]['details']=$tpl_detail[$vvd['cid']];
        }
        $result['status']=1;
        $result['data']=$data;
        return $result;
    }

    public function getWorkDay(Request $request)
    {
        $user_id=\Auth::user()->id;
        $from = $request->input('from');
        $to = $request->input('to');
        if (!$from){
            $from = Carbon::now()->format('Ym')."00";
        }
        if (!$to){
            $to = Carbon::now()->format('Ym')."32";
        }
        $allWorkDays = CompensationDetail::where('user_id', $user_id)
            ->where('compensation_day','>=',$from)
            ->where('compensation_day','<=',$to)->pluck('compensation_day');

        return response()->json(['days'=>$allWorkDays]);
    }
}
