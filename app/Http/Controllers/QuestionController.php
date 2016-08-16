<?php

namespace App\Http\Controllers;

use App\Question;
use App\QuestionTag;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        $this->middleware('binding');
    }

    /**
     * 我的问题页面.
     *
     * @return mixed
     */
    public function myQuestion()
    {
        return view('home.questionMyList');
    }

    /**
     * 最新问题页面.
     *
     * @return mixed
     */
    public function newQuestion()
    {
        return view('home.questionNewList');
    }

    /**
     * 搜索问题页面.
     *
     * @return mixed
     */
    public function searchQuestion()
    {
        return view('home.questionSearch');
    }

    /**
     * 问题列表接口.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $history = $request->input('history', 0);
        $search = $request->input('wd', '');
        $user_id = \Auth::user()->id;
        $model = Question::orderBy('updated_at','desc');
        //判断是否为自己的历史
        if ($history == 1) {
            $model->where('creator',$user_id);
        }else{
            $model->where('status',2);
        }

        //搜索句子
        if ($search){
            $search = mb_substr($search,0,15);
            $tags = QuestionTag::all()->pluck('tag')->toArray();
            $sameTag=[];
            //获取相同tag标签
            foreach ($tags as $k=>$v){
                if (strpos($search,$v)!==false){
                    $sameTag[]=$v;
                }
            }
            //标签不存在
            if (!count($sameTag)){
                $model->where('id', 0);
            }
            //标签存在
            else{
                $model->where(function($query) use ($sameTag){
                    foreach ($sameTag as $k=>$v) {
                        $query->orWhere('tags', 'like', '%' . $v . '%');
                    }
                });
            }
        }

        $info = $model->select(['id','creator','title','answer','type','status','updated_at'])->paginate(15);

        $info->appends(['history' => $history,'wd' => $search]);
        return $info;
    }

    /**
     * 问题创建页面.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('home.questionCreate');
    }

    /**
     * 保存一个问题.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = \Auth::user()->id;

        $tags = QuestionTag::all();
        $needTag = [];
        foreach ($tags as $k=>$v){
            if (strpos($request->input('title'), $v['tag'])!==false){
                $needTag[] = $v['tag'];
            }
        }

        $all = [
            'creator' => $userId,
            'receiver' => 0,
            'title' => $request->input('title'),
            'detail' => $request->input('detail'),
            'type' => $request->input('cat'),
            'status' => 1,
            'tags' => implode(",", $needTag),
        ];

        $qInfo = Question::create($all);
        return redirect('question/create')->with('message','保存成功！');
    }

    /**
     * 查看问题详情.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $info = Question::where('id', $id)->get()->map(function($val){
            $val['answer'] = nl2br($val['answer']);
            return $val;
        })->first();
        
        return view('home.questionDetail',['info'=>$info]);
    }

    /**
     * 编辑问题界面.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('errors.404');
    }

    /**
     * 更新具体问题.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return view('errors.404');
    }

    /**
     * 删除具体问题.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return view('errors.404');
    }
}
