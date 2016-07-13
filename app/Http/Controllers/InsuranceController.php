<?php

namespace App\Http\Controllers;

use App\Events\UserLog;
use App\InsuranceDetail;
use App\ModuleStatistics;
use App\SalaryCategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class InsuranceController extends Controller
{
    public function __construct() {
        $this->middleware("auth");
        $this->middleware('binding');
    }

    public function index(Request $request)
    {
        $user_id = \Auth::user()->id;

        //统计模块访问量
        $moduleData = new ModuleStatistics();
        $moduleData->user_id = $user_id;
        $moduleData->ip = $request->ip();
        $moduleData->module = 'InsuranceProgress';
        \Event::fire(new UserLog($moduleData));

        $insurance = InsuranceDetail::where('user_id', $user_id)->orderBy('created_at','desc')->paginate(15);
        return view('home.insuranceProgress',['insurance'=>$insurance]);
    }

    public function specific($id)
    {
        return view('home.insuranceSpecific',['id'=>$id]);
    }

    public function detail(Request $request)
    {
        $id=$request->input('id');
        $type=$request->input('type');
        if(!$id||!is_numeric($id)||$type!=4){
            return response()->json(["status"=>0],200);
        }

        $user_id=\Auth::user()->id;
        $detail=InsuranceDetail::where("user_id","=",$user_id)
            ->where('type',$type)->find($id);
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
}
