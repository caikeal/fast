<?php

namespace App\Http\Controllers\Admin;

use App\Fast\Service\Answer\Answer;
use App\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AnswerController extends Controller
{
    protected $answer;
    public function __construct(Answer $answer)
    {
        $this->middleware('auth:admin');
        $this->middleware('throttle');
        $this->answer = $answer;
    }

    public function info()
    {
        $managerId = \Auth::guard("admin")->user()->id;
        //获取用户可以回答的问题类型
        $roleNameArr = $this->answer->canAnswerType();
        //查询对应类型的问题(15个/页)
        $allQuestion = Question::whereIn('type', $roleNameArr->collapse())
            ->where('status', 1)->paginate(15);

        return $allQuestion;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.answer');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('errors.404');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return view('errors.404');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $managerId = \Auth::guard("admin")->user()->id;
        //获取用户可以回答的问题类型
        $roleNameArr = $this->answer->canAnswerType();

        //查询对应类型的问题
        $allQuestion = Question::with(['user'=>function ($query) {
            $query->select(['name', 'phone', 'id_card', 'id']);
        }])->whereIn('type', $roleNameArr->collapse())
            ->where('status', 1)->where('id',$id)->first();

        if (!$allQuestion){
            $allQuestion = ['message'=>'NonData'];
        }

        return $allQuestion;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('errors.404');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\Admin\AnswerRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\Admin\AnswerRequest $request, $id)
    {
        $answer = $request->input('answer');
        $managerId = \Auth::guard("admin")->user()->id;
        //获取用户可以回答的问题类型
        $roleNameArr = $this->answer->canAnswerType();

        //查询对应类型的问题
        $questionInfo = Question::whereIn('type', $roleNameArr->collapse())
            ->where('status', 1)->where('id',$id)->first();

        if ($questionInfo){
            $questionInfo->receiver = $managerId;
            $questionInfo->answer = $answer;
            $questionInfo->answer_at = Carbon::now();
            $questionInfo->status = 2;
            $questionInfo->update();
        }else{
            return ['status'=>0, 'message'=>'该问题已被你的同事抢先一步回答了！'];
        }

        return ['status'=>1, 'message'=>'保存成功！'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return view('errors.404');
    }
}
