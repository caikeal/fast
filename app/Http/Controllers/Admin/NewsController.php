<?php

namespace App\Http\Controllers\Admin;

use App\Fast\Service\News\NewsInfo;
use App\Fast\Service\ReuploadApplication\Application;
use App\News;
use App\ReuploadApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    protected $newInfo;

    protected $application;

    public function __construct(NewsInfo $newsInfo, Application $application)
    {
        $this->newInfo = $newsInfo;
        $this->application = $application;
    }

    /**
     * 获取用户所有消息。
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $manager_id = \Auth::guard('admin')->user()->id;
        $news = News::where('receiver', $manager_id)->orderBy('created_at','desc')->paginate(15);
        
        //添加已读状态
        $this->newInfo->isReadMore($manager_id);
        
        return view('admin.news',['news'=>$news]);
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
     * @param  Requests\Admin\NewsAllowRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\Admin\NewsAllowRequest $request, $id)
    {
        $givenStatus = $request->input('st');

        $manager_id = \Auth::guard('admin')->user()->id;
        //先通过查询该消息类型是否能同意
        $newsInfo = News::where('receiver', $manager_id)->find($id);
        if ($newsInfo['type']!=1){
            return response()->json(['invalid'=>'该消息错误！'])->setStatusCode(422);
        }

        //再查询该消息状态是否允许同意
        if ($newsInfo['status']!=3){
            return response()->json(['invalid'=>'该消息已经同意！请勿重新选择'])->setStatusCode(422);
        }

        //再查询该消息状态对应的申请是否允许同意
        $application = ReuploadApplication::where('receiver', $manager_id)->find($newsInfo['relate_id']);
        if ($application['status']!=3){
            return response()->json(['invalid'=>'该消息已经同意！请勿重新选择'])->setStatusCode(422);
        }
        $expiration = $application['created_at']->addDays($application['expiration']);
        if ($application['status']==3 && $expiration < Carbon::now()){
            $this->newInfo->expirate($id);
            $this->application->expirate($newsInfo['relate_id']);
            return response()->json(['invalid'=>'该消息已经过期！请勿重新申请'])->setStatusCode(422);
        }

        //修改消息/申请状态
        \DB::beginTransaction();
        try{
            $content = '';
            if ($givenStatus == 1){
                //agree
                $this->newInfo->agree($id);
                $this->application->agree($newsInfo['relate_id']);
                $content = '您好，管理员'.\Auth::guard("admin")->user()->name
                    .'同意了您申请重新上传表格';
            }elseif($givenStatus == 2){
                //refuse
                $this->newInfo->refuse($id);
                $this->application->refuse($newsInfo['relate_id']);
                $content = '您好，管理员'.\Auth::guard("admin")->user()->name
                    .'拒绝了您申请重新上传表格';
            }

            //发送消息提醒同意或者不同意
            $this->newInfo->storeNews($manager_id, $newsInfo['sender'], 2, $id, $content);
            \DB::commit();
            return response()->json(['ret_num'=>0, 'ret_msg'=>'保存成功！']);
        }catch (\Exception $e){
            \DB::rollBack();
            return response()->json(['error'=>'网络错误！'])->setStatusCode(500);
        }

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
     * 获取未读用户消息。
     * 
     * 具体取5条
     * @return mixed
     */
    public function notify()
    {
        //获取用户未读消息。
        $manager_id = \Auth::guard('admin')->user()->id;
        $unreadNews = News::where('receiver', $manager_id)
            ->where('is_read', 0)->orderBy('created_at', 'desc')->paginate(5);

        return response()->json($unreadNews);
    }
}
