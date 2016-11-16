<?php

namespace App\Http\Controllers;

use App\Events\UserLog;
use App\InsuranceDetail;
use App\ModuleStatistics;
use App\SalaryCategory;
use App\SalaryDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        $this->middleware('binding');
    }

    public function index(Request $request){
        $now = Carbon::now()->format("Y-m");

        $user = \Auth::user()->id;

        //统计模块访问量
        $moduleData = new ModuleStatistics();
        $moduleData->user_id = $user;
        $moduleData->ip = $request->ip();
        $moduleData->module = 'Salary';
        \Event::fire(new UserLog($moduleData));

        return view('home.salary',['now'=>$now]);
    }

    public function insurance(Request $request){
        $now = Carbon::now()->format("Y-m");
        $user = \Auth::user()->id;
        //是否有社保进度历史
        $hasIt = InsuranceDetail::where('user_id', $user)->count();

        //统计模块访问量
        $moduleData = new ModuleStatistics();
        $moduleData->user_id = $user;
        $moduleData->ip = $request->ip();
        $moduleData->module = 'Insurance';
        \Event::fire(new UserLog($moduleData));

        return view('home.insurance', ['is_exist'=>$hasIt, 'now'=>$now]);
    }

    public function detail(Request $request){
        $salary_day=$request->input('time');
        $type=$request->input('type');
        if(!$salary_day||!is_numeric($salary_day)){
            return response()->json(["status"=>0],200);
        }
        $user_id=\Auth::user()->id;
        $detail=SalaryDetail::where("salary_day","=",$salary_day)
            ->where("user_id","=",$user_id)->where('type',$type)->first();
        if(!$detail){
            return response()->json(["status"=>0],200);
        }
        $cats=$detail->baseCategory()->orderBy("place","asc")->get();
        $catsName=array();
        foreach($cats as $k=>$v){
            $catsName[]=SalaryCategory::where("id","=",$v['category_id'])->first();
        }
        //组织主干数据
        $data = $this->organizeDetail($catsName, $detail->wages);

        //组织附加数据
        if ($detail->meta) {
            $balanceTpl = json_decode($detail->meta, true);
            $balance['balance'] = [];
            if ($balanceTpl['balance']) {
                $balance['balance'] = collect($balanceTpl['balance'])->map(function ($item, $index) use ($catsName){
                    $item['details'] = $this->organizeDetail($catsName, $item['wages']);
                    unset($item['wages']);
                    return $item;
                })->values()->all();
            }
        }

        $result['status']=1;
        $result['data']=$data;
        $result['meta']=$balance?$balance:[];
        return $result;
    }

    /**
     * 组织详情返回数据结构.
     * @param $catsName
     * @param $wagesStr
     * @return array|mixed
     */
    protected function organizeDetail ($catsName, $wagesStr) {
        $data=array();
        $tpl_detail=array();
        $data = $this->getFirstDetail($catsName)[0];
        $tpl_detail = $this->getFirstDetail($catsName)[1];

        $wages=explode("||", $wagesStr);
        $main_sub_detail = $this->getSubDetail($tpl_detail, $wages);
        foreach($data as $kkd=>$vvd){
            $data[$kkd]['details']=$main_sub_detail[$vvd['cid']];
        }
        return $data;
    }

    /**
     * 获取一级类型的值.
     * @param $catsName
     * @return array
     */
    protected function getFirstDetail ($catsName) {
        $flag_id=0;
        $data=array();
        $tpl_detail=array();
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

        return [$data, $tpl_detail];
    }

    /**
     * 获取二级类型的值.
     * @param $tpl_detail
     * @param $wages
     * @return mixed
     */
    protected function getSubDetail ($tpl_detail, $wages) {
        $count_arr=0;
        foreach($tpl_detail as $kt=>$vt){
            foreach($vt as $kd=>$vd){
                $tpl_detail[$kt][$kd]['v']=key_exists($count_arr,$wages)?($wages[$count_arr]?$wages[$count_arr]:""):"";
                $count_arr++;
            }
        }

        return $tpl_detail;
    }
}
