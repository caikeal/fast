<?php

namespace App\Http\Controllers\Admin;

use App\Fast\Service\History\History;
use App\Fast\Service\Tools\UserAgentTrait;
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

    public function __construct(History $history)
    {
        $this->middleware('auth:admin');
        $this->history = $history;
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
                $query->orderBy('created_at','desc')->first();
            }])->where('manager_id', $manager_id);

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
}
