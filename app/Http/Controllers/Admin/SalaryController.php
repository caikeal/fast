<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\SalaryBase;
use App\SalaryDetail;
use App\SalaryTask;
use App\SalaryUpload;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\SalaryBaseRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class SalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function timeline(){
        //取2个月的薪资任务
        $now=Carbon::now();
        $next=Carbon::now()->addMonth();
        $next2=Carbon::now()->addMonths(2);
        $nowMonthTime=strtotime($now->year."-".$now->month."-1");
        $nextMonthTime=strtotime($next->year."-".$next->month."-1");
        $next2MonthTime=strtotime($next2->year."-".$next2->month."-1");
        $tasks=SalaryTask::where("deal_time",">=",$nowMonthTime)->where("deal_time","<",$next2MonthTime)
            ->where("receive_id","=",\Auth::guard('admin')->user()->id)->orderBy("deal_time","asc")->get();
        $data=[
            'tasks'=>$tasks,
            'now'=>$now,
            'next'=>$next,
            'nextMonthTime'=>$nextMonthTime,
        ];

        return view('admin.timeline',$data);
    }

    public function base(SalaryBaseRequest $request){
        $cats=$request->input('category');
        $cid=$request->input('cid');
        $title=$request->input('title');
        $mid=\Auth::guard('admin')->user()->id;
        $now=Carbon::now();
        \DB::beginTransaction();
        try{
            $base_id=\DB::table('salary_base')->insertGetId([
                'title'=>$title,
                'manager_id'=>$mid,
                'company_id'=>$cid,
                'created_at'=>$now,
                'updated_at'=>$now,
            ]);
            foreach($cats as $k=>$cat) {
                if ($cat) {
                    \DB::table('salary_base_category')->insert([
                        'base_id' => $base_id,
                        'category_id' => $cat,
                        'place' => $k,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
            \DB::commit();
        }
        catch(\Exception $e){
            \DB::rollBack();
//            throw $e;
        } catch (\Throwable $e) {
            \DB::rollBack();
//            throw $e;
        }
        return redirect('admin/timeline');
    }

    public function download(Request $request){
        $base_id=$request->get('bid');
        $base=SalaryBase::find($base_id);
        $cats=$base->categories()->orderBy('place','asc')->get();
        Excel::create($base['title'], function($excel) use($cats,$base) {

            // Set the title
            $excel->setTitle($base->id."");

            // Chain the setters
            $excel->setCreator('keal')
                ->setCompany('FESCO');

            // Call them separately
            $excel->setDescription('A Base For Salary Made By Keal');

            $data=array();
            $datum=array("姓名","身份证号","发薪日（格式如：201601）");
            foreach($cats as $cat) {
                $datum[]=$cat['name'];
            }
            $data=array($datum);

            $excel->sheet($base['company_id']."", function($sheet) use($data) {
                $sheet->fromArray($data);
            });
        })->download('xlsx');
    }

    public function upload(Request $request){
        if(!$request->file('excel')->isValid()){
            return response("failed",404);
        }
        $task_id=$request->get('task_id');
        $nameFile=$request->get('name');
        $extension=explode(".",$nameFile);
        $now=Carbon::now();
        if($extension[1]) {
            $name = 'import/excel/'.$now->year . "-" . $now->month . "/" . time().".".$extension[1];
        }else{
            $name = 'import/excel/'.$now->year . "-" . $now->month . "/" . time().".xls";
        }
        $way=\Storage::put($name,file_get_contents($request->file('excel')->getRealPath()));
        if(!$way){
            return response("failed",404);
        }

        /*
         * 读取excel
         */
        $workExcel=Excel::selectSheetsByIndex(0)->load(storage_path('app/'.$name));
        $workSheet=$workExcel->sheet(0);
        $base_id=$workExcel->getTitle();
        $company_id=$workSheet->getTitle();
        $companyExist=SalaryTask::where("company_id","=",$company_id)
            ->where("receive_id","=",\Auth::guard('admin')->user()->id)
            ->where("status","=","0")->where("id","=",$task_id)->count();//工资任务存在,且未完成
        $baseExist=SalaryBase::where("id","=",$base_id)->count();//工资模版存在
        if(!$base_id||!$company_id||!is_numeric($base_id)||!is_numeric($company_id)||!$companyExist||!$baseExist){
            \Storage::delete($name);
            return response("liner",404);//格式不正确
        }
        //记录上传者
        $salaryUpload=new SalaryUpload();
        $salaryUpload->manager_id=\Auth::guard('admin')->user()->id;
        $salaryUpload->base_id=$base_id;
        $salaryUpload->upload='app/'.$name;
        $salaryUpload->save();

        $content=$workExcel->get()->toArray();
        if(count($content)<2){
            \Storage::delete($name);
            return response("No Data",404);//必须有实际数据
        }
        Cache::store('file')->put('admin_salaryUp:'.$base_id."|".$company_id, json_encode($content), 60);
        //todo will give a confirmation in future

        $res=$this->store($content,$base_id,$company_id,$task_id);
        if(!$res){
            return response("Again",404);//有实际数据出错
        }

        return json_encode($content);
    }

    protected function store($content,$base_id,$company_id,$task_id){
        $fail=0;
        $now=Carbon::now();
        $manager_id=\Auth::guard('admin')->user()->id;
        foreach($content as $k=>$v){
            if($k>0){
                //开启事务
                $is_exist_user=User::where("id_card","=",$v[1])->first();
                $is_exist_detail="";
                if($is_exist_user) {
                    $is_exist_detail = SalaryDetail::where("user_id", "=", $is_exist_user->id)
                        ->where("company_id","=",$company_id)
                        ->where("salary_day","=",$v[2])
                        ->first();
                }
                DB::beginTransaction();
                try{
                    //用户创建
                    if(!$is_exist_user) {
                        $user_id=DB::table('users')->insertGetId([
                            'name' => $v[0],
                            'id_card'=>$v[1],
                            'password'=>bcrypt(substr($v[1],-6)),
                            'manager_id'=>$manager_id,
                            'created_at'=>$now,
                            'updated_at'=>$now,
                        ]);
                    }else{
                        $user_id=$is_exist_user->id;
                    }
                    //薪资数据保存
                    $wages="";
                    foreach($v as $kk=>$vv){
                        if($kk>2){
                            $wages.=$vv.",";
                        }
                    }
                    $wages=trim($wages,",");
                    if(!$is_exist_detail) {
                        DB::table('salary_details')->insert([
                            'user_id' => $user_id,
                            'base_id' => $base_id,
                            'company_id' => $company_id,
                            'wages' => $wages,
                            'salary_day' => $v[2],
                            'manager_id' => $manager_id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }else{
                        DB::table('salary_details')->where('company_id',"=", $company_id)
                            ->where('salary_day',"=",$v[2])
                            ->where('user_id',"=",$user_id)->update([
                            'base_id' => $base_id,
                            'wages' => $wages,
                            'manager_id' => $manager_id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                    //提交事务
                    DB::commit();
                }catch (\Exception $e){
                    $fail=1;
                    DB::rollBack();
                }
            }
        }
        if($fail!=1){
            DB::table('salary_task')->where("company_id","=",$company_id)
                ->where("receive_id","=",$manager_id)->where("id","=",$task_id)
                ->update(["status"=>1]);
        }else{
            return false;
        }
        return true;
    }
}
