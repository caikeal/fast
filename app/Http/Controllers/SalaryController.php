<?php

namespace App\Http\Controllers;

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
    }

    public function index(){
        return view('home.salary');
    }

    public function detail(Request $request){
        $salary_day=$request->time;
        if(!$salary_day||!is_numeric($salary_day)){
            return response()->json(["status"=>0],200);
        }
        $user_id=\Auth::user()->id;
        $detail=SalaryDetail::where("salary_day","=",$salary_day)
            ->where("user_id","=",$user_id)->first();
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
        $wages=explode(",",$detail->wages);
        foreach($tpl_detail as $kt=>$vt){
            foreach($vt as $kd=>$vd){
                $tpl_detail[$kt][$kd]['v']=$wages[$kd]?$wages[$kd]:"";
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
