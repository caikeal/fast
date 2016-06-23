<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Fast\Service\Excel\SalaryExcel;
use App\Jobs\SaveUploadSalary;
use App\SalaryBase;
use App\SalaryTask;
use App\SalaryUpload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\SalaryBaseRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class SalaryController extends Controller
{
    protected $excel;

    public function __construct(SalaryExcel $excel)
    {
        $this->middleware('auth:admin');
        $this->excel = $excel;
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
            ->where("receive_id","=",\Auth::guard('admin')->user()->id)->where("type",1)
            ->orderBy("deal_time","asc")->get();
        $data=[
            'tasks'=>$tasks,
            'now'=>$now,
            'next'=>$next,
            'nextMonthTime'=>$nextMonthTime,
        ];

        return view('admin.timeline',$data);
    }

    public function insurance(){
        //取2个月的薪资任务
        $now=Carbon::now();
        $next=Carbon::now()->addMonth();
        $next2=Carbon::now()->addMonths(2);
        $nowMonthTime=strtotime($now->year."-".$now->month."-1");
        $nextMonthTime=strtotime($next->year."-".$next->month."-1");
        $next2MonthTime=strtotime($next2->year."-".$next2->month."-1");
        $tasks=SalaryTask::where("deal_time",">=",$nowMonthTime)->where("deal_time","<",$next2MonthTime)
            ->where("receive_id","=",\Auth::guard('admin')->user()->id)->where("type",2)
            ->orderBy("deal_time","asc")->get();

        //社保进度模版
        $bases = SalaryBase::where('type',4)->get(['id', 'title']);

        $data=[
            'tasks'=>$tasks,
            'now'=>$now,
            'next'=>$next,
            'nextMonthTime'=>$nextMonthTime,
            'bases'=>$bases
        ];

        return view('admin.insurance',$data);
    }

    /**
     * 模版的创建。
     * @param SalaryBaseRequest $request
     * @return mixed
     */
    public function base(SalaryBaseRequest $request){
        $cats=$request->input('category');
        $cid=$request->input('cid');
        $title=$request->input('title');
        $type=$request->input('type');
        $mid=Auth::guard('admin')->user()->id;
        $now=Carbon::now();
        DB::beginTransaction();
        try{
            $base_id=DB::table('salary_base')->insertGetId([
                'title'=>$title,
                'manager_id'=>$mid,
                'company_id'=>$cid,
                'type'=>$type,
                'created_at'=>$now,
                'updated_at'=>$now,
            ]);
            $count = SalaryBase::where('title', $title)
                ->where('type', $type)
                ->where('company_id', $cid)->count();
            if ($count > 1){
                throw new \Exception('标题重复！', 100);
            }
            foreach($cats as $k=>$cat) {
                if ($cat) {
                    DB::table('salary_base_category')->insert([
                        'base_id' => $base_id,
                        'category_id' => $cat,
                        'place' => $k,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
            DB::commit();
        }
        catch(\Exception $e){
            DB::rollBack();
            if ($e->getCode() == 100){
                return response()->json(['title'=>$e->getMessage()])->setStatusCode(422);
            }else{
                return response()->json(['network'=>'网络错误！'])->setStatusCode(500);
            }
        }

        if($type==1){
            $data['url'] = url('admin/timeline');
        }elseif($type==2){
            $data['url'] = url('admin/insurance');
        }elseif($type==3){
            $data['url'] = url('admin/compensation');
        }elseif($type==4){
            $data['url'] = url('admin/insurance');
        }else{
            $data['url'] = '';
        }

        $result['ret_num']=0;
        $result['ret_msg']='保存成功！';
        $result['data']=$data;
        return response()->json($result);
    }

    /**
     * 下载模板.
     * type=1表示工资模板，
     * type=2表示社保模板，
     * type=3表示理赔模板，
     * type=4表示社保进度模版
     * @param Request $request
     */
    public function download(Request $request){
        $ua = strtolower($_SERVER["HTTP_USER_AGENT"]);
        $base_id=$request->get('bid');
        $base=SalaryBase::find($base_id);
        $cats=$base->categories()->where("level",2)->orderBy('place','asc')->get();

        //解决不同浏览器下载excel时标题解析乱码问题
        if (preg_match("/msie|edge|safari|firefox/", $ua)) {
            $base_title=urlencode($base['title']);
        }else{
            $base_title=$base['title'];
        }
        Excel::create($base_title, function($excel) use($cats,$base) {

            // Set the title
            $excel->setTitle($base->id."");

            // Chain the setters
            $excel->setCreator('keal')
                ->setCompany('FESCO');

            // Call them separately
            $excel->setDescription('A Base For Salary Made By Keal');

            $data=array();
            if ($base['type'] == 3){
                $datum=array("姓名","身份证号","查询日（格式如：20160102）");
            }else{
                $datum=array("姓名","身份证号","发薪日（格式如：201601）");
            }
            foreach($cats as $cat) {
                $datum[]=$cat['name'];
            }
            $data=array($datum);

            $excel->sheet($base['company_id']."", function($sheet) use($data) {
                $sheet->fromArray($data);
            });
        })->download('xlsx');
    }

    /**
     * 上传模板数据.
     * @param Request $request
     * @return mixed
     */
    public function upload(Request $request){
        set_time_limit(1800) ;
        //验证excel格式是否正确
        if(!$request->file('excel')->isValid()){
            return response("failed",422);
        }

        $task_id=$request->get('task_id');
        $nameFile=$request->get('name');
        $type=$request->get('type');
        
        $extension=explode(".",$nameFile);
        if($extension[1]) {
            $fileName = "/" . time().".".$extension[1];
        }else{
            $fileName = "/" . time().".xls";
        }

        //保存excel
        $isStore = $this->excel->store($fileName, file_get_contents($request->file('excel')->getRealPath()));

        if(!$isStore){
            $this->excel->delete();
            return response("failed",404);
        }

        //读取excel基础数据
        $workExcel = $this->excel->read();
        $base_id = $this->excel->getWorkTitle();
        $workSheet = $this->excel->getSheet(0);
        $company_id=$this->excel->getSheetTitle();

        //验证基础数据正确性
        $companyExist=SalaryTask::where("company_id","=",$company_id)
            ->where("receive_id","=",\Auth::guard('admin')->user()->id)
            ->where("status","=","0")->where("id","=",$task_id)->count();//工资任务存在,且未完成
        $baseExist=SalaryBase::where("id", $base_id)
            ->where('company_id', $company_id)
            ->where('type', $type)->count();//工资模版存在
        if(($type!=1 && $type!=2)||!$base_id||!$company_id||!is_numeric($base_id)||!is_numeric($company_id)||!$companyExist||!$baseExist){
            $this->excel->delete();
            return response("liner",422);//格式不正确
        }

        //读取excel内容
        $content=$this->excel->content();
        foreach($content as $k=>$v){
            //去除excel空姓名行
            if(!$v[0]){
                unset($content[$k]);
            }
        }

        //空格数据表验证
        if(count($content)<2){
            $this->excel->delete();
            return response("No Data",422);//必须有实际数据
        }

        //将数据存入缓存
        Cache::store('file')->put('admin_salaryUp:'.$base_id."|".$company_id."|".$task_id, json_encode($content), 60);

        unset($content);
        $manager_id=\Auth::guard('admin')->user()->id;

        DB::beginTransaction();
        try{
            //记录上传者
            $salaryUpload=new SalaryUpload();
            $salaryUpload->manager_id=$manager_id;
            $salaryUpload->base_id=$base_id;
            $salaryUpload->company_id=$company_id;
            $salaryUpload->type=$type;
            $salaryUpload->upload='app/'.$this->excel->getPath();
            $salaryUpload->save();

            //更新任务状态
            SalaryTask::where("company_id", "=", $company_id)->where("type", $type)
                ->where("receive_id", "=", $manager_id)->where("id", "=", $task_id)
                ->update(["status" => 2]);

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            $this->excel->delete();
            return response("Save Wrong",422);//保存失败
        }

        //推送至队列异步执行插入
        $this->dispatch(new SaveUploadSalary($base_id,$company_id,$task_id,$type,$manager_id));
        
        return response("success");
    }

}
