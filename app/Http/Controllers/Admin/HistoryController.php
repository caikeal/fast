<?php

namespace App\Http\Controllers\Admin;

use App\Fast\Service\Excel\SalaryExcel;
use App\Fast\Service\History\History;
use App\Fast\Service\Tools\UserAgentTrait;
use App\Jobs\ReuploadSalary;
use App\ReuploadApplication;
use App\SalaryBase;
use App\SalaryUpload;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HistoryController extends Controller
{
    use UserAgentTrait;

    protected $history;

    protected $excel;

    public function __construct(History $history, SalaryExcel $excel)
    {
        $this->middleware('auth:admin');
        $this->history = $history;
        $this->excel = $excel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = $request->input('company');
        $from = $request->input('from');
        $to = $request->input('to');

        //格式化时间
        if ($from && $to){
            $from = Carbon::parse($from)->toDateTimeString();
            $to = Carbon::parse($to)->toDateTimeString();
        }

        $manager_id = \Auth::guard('admin')->user()->id;
        $roleLevel = \Auth::guard('admin')->user()->roles()->first()->level;
        //查询上传记录
        $searchCondition = SalaryUpload::with([
            'application'=>function($query){
                $query->where('status', '!=', 4)
                    ->where('status', '!=', 2)
                    ->orderBy('created_at','desc');
            }])
            ->where('manager_id', $manager_id);

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

        return view('admin.history', ['uploads'=>$allUploads, 'roleLevel'=>$roleLevel, 'company'=>$company, 'from'=>$from, 'to'=>$to]);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * 缓存处理
     * 上传凭证=》身份凭证：序列号；
     * 上传凭证：身份凭证=》序列号；
     * 上传凭证：身份凭证：序列号=》数据内容；
     * 上传凭证：page=》总页数
     * 上传凭证：head=>表格头
     */
    public function show($id, Request $request)
    {
        $p = $request->input('page',1);
        $name = $request->input('name');

        $managerId = \Auth::guard('admin')->user()->id;

        //搜索姓名或者身份证号，都转化为搜索身份证号
        $indentity = [];
        $data = [];
        $head = [];
        $maxPage = 0;
        if ($name){
            $users = User::where('id_card', 'like', '%'.$name.'%')
                ->orWhere('name', 'like', '%'.$name.'%')->get(['id_card']);
            foreach($users as $v){
                $indentity[] = $v['id_card'];
            }
        }

        //如果有搜索名字，且能搜索出来 或者没有搜索名字的
        if (($name && count($indentity)) || !$name) {
            $uploads = SalaryUpload::where('manager_id', $managerId)->find($id);
            if (!$uploads) {
                return response()->json(['invalid' => '该记录已失效'])->setStatusCode(404);
            }

            //是否有历史缓存
            $isCache = $this->history->hasCache($id);
            $data = [];
            $head = [];
            $maxPage = 0;

            //有，取出缓存数据
            if ($isCache) {
                $maxPage = $this->history->getMaxPage($id);
                if ($p <= $maxPage && $p >= 0) {
                    $data = $this->history->getCache($id, $p, $indentity);
                } else {
                    $data = [];
                }
                $head = \Cache::store('redis')->get("history:".$id . ":head");
                $maxPage = $this->history->getMaxPage($id);
            }

            //没有，读取表格加入缓存
            if (!$isCache) {
                $contentArr = $this->history->getFile($uploads['upload']);
                $maxPage = $this->history->storeCache($contentArr, $id);

                if ($p <= $maxPage && $p >= 0) {
                    $data = $this->history->getCache($id, $p, $indentity);
                } else {
                    $data = [];
                }
                $head = \Cache::store('redis')->get("history:".$id . ":head");
            }
        }

        //输出数据
        $result['ret_num'] = 0;
        $result['ret_msg'] = "操作成功！";
        $result['data'] = $data;
        $result['head'] = $head;
        $result['max_page'] = $maxPage;
        $result['name'] = $name;
        return response()->json($result);
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

    /**
     * 历史文件下载。
     *
     * @param Request $request
     * @return mixed
     */
    public function download(Request $request)
    {
        $uploadId = $request->input('upload_id');
        $managerId = \Auth::guard('admin')->user()->id;
        $ua = strtolower($_SERVER["HTTP_USER_AGENT"]);

        //没有参数时
        if (!$uploadId){
            return response()->json(['upload_id'=>'缺少参数！'])->setStatusCode(422);
        }

        $uploads = SalaryUpload::with('company')->where('manager_id', $managerId)->find($uploadId);

        //非法访问
        if (!$uploads){
            return response()->json(['invalid'=>'该文件已失效！'])->setStatusCode(404);
        }

        //该文件已过保存期
        if (!$this->history->hasFile($uploads['upload'])){
            return response()->json(['invalid'=>'该文件已失效！'])->setStatusCode(404);
        }

        $fileName = $this->history->getFileName($uploads['upload']);

        //解决不同浏览器下载excel时标题解析乱码问题
        $base_title = $this->beautyFileName($fileName, $ua);

        return response()->download(storage_path($uploads['upload']), $base_title);
    }

    public function reupload(Request $request)
    {
        set_time_limit(1800) ;
        //验证excel格式是否正确
        if(!$request->file('excel')->isValid()){
            return response("failed",422);
        }

        $upload_id=$request->get('upload_id');
        $reupload_id=$request->get('reupload_id');
        $company=$request->get('company');
        $nameFile=$request->get('name');
        $type=$request->get('type');

        $manager_id = \Auth::guard('admin')->user()->id;

        $extension=explode(".",$nameFile);
        if($extension[1]) {
            $fileName = time().".".$extension[1];
        }else{
            $fileName = time().".xls";
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

        //=================================验证基础数据正确性
        //工资上传是存在的
        $uploadExist=SalaryUpload::where("company_id", $company_id)
            ->where("manager_id", $manager_id)
            ->where("id", $upload_id)->count();

        //工资模版存在
        $baseExist=SalaryBase::where("id", $base_id)
            ->where('company_id', $company_id)
            ->where('type', $type)->count();

        //验证申请已经同意(1级管理员直接默认同意)
        $roleLevel = \Auth::guard('admin')->user()->roles()->first()->level;
        if ($roleLevel==1){
            $applicatonExist = 1;
        }else{
            $applicatonExist = ReuploadApplication::where('upload_id', $upload_id)
                ->where('id', $reupload_id)->where('status',1)
                ->where('applier', $manager_id)->count();
        }


        if(($type!=1 && $type!=2)||!$base_id||!$company_id
            ||!is_numeric($base_id)||!is_numeric($company_id)
            || $company_id!=$company ||!$uploadExist||!$baseExist
            ||!$applicatonExist){
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
        \Cache::store('file')->put('admin_salaryReUp:'.$base_id."|".$company_id."|".$reupload_id."|".$upload_id, json_encode($content), 60);
        unset($content);

        \DB::beginTransaction();
        try{
            //记录上传者
            $salaryUpload=new SalaryUpload();
            $salaryUpload->manager_id=$manager_id;
            $salaryUpload->base_id=$base_id;
            $salaryUpload->company_id=$company_id;
            $salaryUpload->type=$type;
            $salaryUpload->upload='app/'.$this->excel->getPath();
            $salaryUpload->save();

            //关闭申请
            ReuploadApplication::where('id', $reupload_id)->update(["status" => 4]);

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollBack();
            $this->excel->delete();
            return response("Save Wrong",422);//保存失败
        }

        //推送至队列异步执行插入
        $this->dispatch(new ReuploadSalary($base_id,$company_id,$reupload_id,$type,$manager_id,$upload_id));

        return response("success");
    }
}
