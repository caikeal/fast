<?php

namespace App\Http\Controllers\Admin;

use App\Fast\Service\Answer\Answer;
use App\Question;
use App\SalaryTask;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $answer;
    public function __construct(Answer $answer)
    {
        $this->middleware('auth:admin');
        $this->answer = $answer;
    }

    public function index(){
        $nowMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->startOfMonth()->subMonths(1);

        //判断首次登录完善个人信息
        $manager = Auth::guard('admin')->user();

        //============获取个人上月的任务缓存============
        //薪资任务
        $hasLastMonthSalaryData = \Cache::store('redis')->has("salaryTasksCompute:".$manager['id'].":".$lastMonth->format("Ym"));
        if ($hasLastMonthSalaryData){
            $lastMonthSalaryData = \Cache::store('redis')->get("salaryTasksCompute:".$manager['id'].":".$lastMonth->format("Ym"));
        }else{
            $lastMonthSalaryData = SalaryTask::where('type', 1)
                ->where('receive_id', $manager['id'])
                ->where('salary_day', $lastMonth->format("Ym"))->count();
            \Cache::store('redis')->put("salaryTasksCompute:".$manager['id'].":".$lastMonth->format("Ym"), $lastMonthSalaryData, 44640);
        }

        //社保任务
        $hasLastMonthInsuranceData = \Cache::store('redis')->has("insuranceTasksCompute:".$manager['id'].":".$lastMonth->format("Ym"));
        if ($hasLastMonthInsuranceData){
            $lastMonthInsuranceData = \Cache::store('redis')->get("insuranceTasksCompute:".$manager['id'].":".$lastMonth->format("Ym"));
        }else{
            $lastMonthInsuranceData = SalaryTask::where('type', 2)
                ->where('receive_id', $manager['id'])
                ->where('salary_day', $lastMonth->format("Ym"))->count();
            \Cache::store('redis')->put("insuranceTasksCompute:".$manager['id'].":".$lastMonth->format("Ym"), $lastMonthInsuranceData, 44640);
        }

        //============获取个人本月的任务数============
        //薪资任务
        $nowMonthSalaryData = SalaryTask::where('type', 1)
            ->where('receive_id', $manager['id'])
            ->where('salary_day', $nowMonth->format("Ym"))->count();

        //社保任务
        $nowMonthInsuranceData = SalaryTask::where('type', 2)
            ->where('receive_id', $manager['id'])
            ->where('salary_day', $nowMonth->format("Ym"))->count();

        //============比例============
        //薪资比例
        if ($nowMonthSalaryData - $lastMonthSalaryData == 0){
            $salaryRate = 0;
        }else if ($lastMonthSalaryData == 0){
            $salaryRate = 1;
        }else{
            $salaryRate = ($nowMonthSalaryData - $lastMonthSalaryData)/$lastMonthSalaryData;
        }

        //社保比例
        if ($nowMonthInsuranceData - $lastMonthInsuranceData == 0){
            $insuranceRate = 0;
        }else if ($lastMonthInsuranceData == 0){
            $insuranceRate = 1;
        }else{
            $insuranceRate = ($nowMonthInsuranceData - $lastMonthInsuranceData)/$lastMonthInsuranceData;
        }

        //============获取当前的答疑解惑数============
        //获取用户可以回答的问题类型
        $roleNameArr = $this->answer->canAnswerType();
        $questionData = Question::whereIn('type', $roleNameArr->collapse())
            ->where('status',1)
            ->count();

        return view('admin.index', [
            "manager" => $manager,
            "type" => $roleNameArr->collapse(),
            "salaryTask" => $nowMonthSalaryData,
            "insuranceTask" => $nowMonthInsuranceData,
            "salaryRate" => $salaryRate,
            "insuranceRate" => $insuranceRate,
            "questionData" => $questionData
        ]);
    }
}
