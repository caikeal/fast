<?php

namespace App\Http\Controllers\Admin;

use App\Fast\Service\News\NewsInfo;
use App\ReuploadApplication;
use App\SalaryUpload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Fast\Service\ReuploadApplication\Application;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TaskApplicationController extends Controller
{
    protected $reuploadApplication;

    protected $news;

    public function __construct(Application $reuploadApplication, NewsInfo $news)
    {
        $this->reuploadApplication = $reuploadApplication;
        $this->news = $news;
        $this->middleware('auth:admin');
        $this->middleware('throttle');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * 提出重新上传申请。
     * 有申请，判断是否过期，无过期，提醒；有过期，向上级申请
     * 无申请，向上级申请
     *
     * @param  Requests\Admin\TaskApplicationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\Admin\TaskApplicationRequest $request)
    {
        $managerId = \Auth::guard("admin")->user()->id;

        //判断是否是1级管理员，是则提醒，不能申请（疑似漏洞），否获取上级用户id
        $level = \Auth::guard("admin")->user()->roles()->first()->level;
        if ($level == 1){
            return response()->json(['invalid'=>'您无权申请'])->setStatusCode(422);
        }

        $leaderId = \Auth::guard("admin")->user()->leader()->first()->id;
        

        $uploadId = $request->input('upload_id');
        //判断改id是否是该用户所上传的
        $isOwnUpload = SalaryUpload::with('company')->where('manager_id', $managerId)->find($uploadId);
        if (!$isOwnUpload || ($isOwnUpload['type']!=1 && $isOwnUpload['type']!=2)){
            return response()->json(['invalid'=>'您无权申请'])->setStatusCode(422);
        }

        //取最近状态
        $application = ReuploadApplication::where('applier', $managerId)
            ->where('upload_id', $uploadId)
            ->orderBy('created_at','desc')->first();

        //消息存在，最近状态是1则提示直接上传
        if ($application && $application['status'] ==1){
            return response()->json(['invalid'=>'上级已同意，请直接上传！'])->setStatusCode(422);
        }

        //消息是否存在，最近状态是否3，判断是否过期
        $time = 0;
        $expiration = 0;
        if ($application){
            $time = $application['created_at'];
            $expiration = $time->addDays($application['expiration']);
        }

        //消息存在，最近状态是，未过期,提示等待回执
        if ($application && $application['status'] ==3 && $expiration>=Carbon::now()){
            return response()->json(['invalid'=>'您已申请，请耐心等待回执！'])->setStatusCode(422);
        }

        \DB::beginTransaction();
        try{
            //消息内容
            $tplType = $isOwnUpload['type']==1?'薪资':($isOwnUpload['type']==2?'社保':'');
            $content = '您好，'.\Auth::guard("admin")->user()->name
                .'向您申请重新上传'.$isOwnUpload->company->name
                .''.$isOwnUpload->created_at.'的'.$tplType.'表格';

            //消息存在，最近状态不是3或消息不存在，新建申请\消息
            if (!$application || ($application['status'] !=3 && $application['status'] !=1)){
                $tplApp = $this->reuploadApplication->saveApplication($managerId, $leaderId, $uploadId);
                $this->news->storeNews($managerId, $leaderId, 1, $tplApp['id'], $content);
            }

            //消息存在，最近状态是3，已过期，将3=>4
            if ($application && $application['status'] ==3 && $expiration<Carbon::now()){
                $application->status = 4;
                $application->update();

                //重新申请\消息
                $tplApp = $this->reuploadApplication->saveApplication($managerId, $leaderId, $uploadId);
                $this->news->storeNews($managerId, $leaderId, 1, $tplApp['id'], $content);
            }
            \DB::commit();
            $result['ret_num'] = 0;
            $result['ret_msg'] = '申请成功！';
        }catch (\Exception $e){
            \DB::rollBack();
            $result['ret_num'] = 12;
            $result['ret_msg'] = '申请失败！';
        }

        //发送请求
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
}
