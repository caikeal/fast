<?php

namespace App\Http\Controllers;

use App\InsuranceDetail;
use App\SalaryCategory;
use App\SalaryDetail;
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

    public function index(){
        return view('home.salary');
    }

    public function insurance(){
        $user = \Auth::user()->id;
        //是否有社保进度历史
        $hasIt = InsuranceDetail::where('user_id', $user)->count();
        return view('home.insurance', ['is_exist'=>$hasIt]);
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
