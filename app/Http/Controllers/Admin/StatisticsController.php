<?php

namespace App\Http\Controllers\Admin;

use App\Fast\Service\Statistics\StatisticsAnalyse;
use App\ModuleStatistics;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{
    /**
     * @var StatisticsAnalyse
     */
    protected $statistics;
    public function __construct(StatisticsAnalyse $statistics)
    {
        $this->middleware('auth:admin');
        $this->middleware('throttle');
        $this->statistics = $statistics;
    }

    /**
     * 总访问次数.
     *
     * @return array
     */
    public function visitLastTimes()
    {
        $nowMonth = Carbon::now()->firstOfMonth();
        $lastMonth = Carbon::now()->firstOfMonth()->subMonth();

        //判断是否有权限使用
        if(\Gate::foruser(\Auth::guard('admin')->user())->denies('statistics')){
            return \Response::json(['invalid'=>'您无权访问'])->setStatusCode(403);
        }

        //===============查询每月历史缓存是否存在，存在直接用，不存在查询该月并加入往期缓存===============
        $hasCache = \Cache::store('file')->has('visitTimes:'.$lastMonth);
        //存在缓存
        if ($hasCache){
            $visitTimes = \Cache::store('file')->get('visitTimes:'.$lastMonth);
        }
        //不存在缓存
        else{
            //查询
            $visitTimesTpl = ModuleStatistics::where('created_at', '<', $nowMonth)->groupBy(['months','module'])
                ->orderBy('module','desc')->orderBy('months','asc')
                ->select(\DB::raw('DATE_FORMAT(created_at,\'%Y%m\') months, module, count(id) count'))
                ->get();

            $allMonths = $this->statistics->getAnalyseMonth();

            //初始化访问次数基础数据
            $baseData = [];
            $allMonthsVisitsTpl = [];
            foreach ($allMonths as $v){
                $baseData['Salary'][$v] = 0;
                $baseData['InsuranceProgress'][$v] = 0;
                $baseData['Insurance'][$v] = 0;
                $baseData['Compensation'][$v] = 0;
                $allMonthsVisitsTpl[$v] = 0;
            }

            //整理数据格式，各个模块访问次数
            foreach ($visitTimesTpl as $k=>$v) {
                $allMonthsVisitsTpl[$v['months']] += $v['count'];
                switch ($v['module']) {
                    case 'Salary':
                    {
                        $baseData['Salary'][$v['months']] = $v['count'];
                        break;
                    }
                    case 'InsuranceProgress':
                    {
                        $baseData['InsuranceProgress'][$v['months']] = $v['count'];
                        break;
                    }
                    case 'Insurance':
                    {
                        $baseData['Insurance'][$v['months']] = $v['count'];
                        break;
                    }
                    case 'Compensation':
                    {
                        $baseData['Compensation'][$v['months']] = $v['count'];
                        break;
                    }
                    default :
                        break;
                }
            }

            $visits = [];
            $legend = [];

            foreach ($baseData as $k=>$v) {
                $tplCount = [];
                foreach ($v as $vv){
                    $tplCount[] = $vv;
                }

                switch ($k){
                    case 'Salary':
                    {
                        $visits[] = [
                            'name' => '薪资模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '薪资模块';
                        break;
                    }
                    case 'InsuranceProgress':
                    {
                        $visits[] = [
                            'name' => '社保进度模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '社保进度模块';
                        break;
                    }
                    case 'Insurance':
                    {
                        $visits[] = [
                            'name' => '社保模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '社保模块';
                        break;
                    }
                    case 'Compensation':
                    {
                        $visits[] = [
                            'name' => '理赔模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '理赔模块';
                        break;
                    }
                    default :
                        break;
                }

            }

            //加入总计模块
            $legend[] = '总访问次数';
            $visits[] = [
                'name' => '总访问次数',
                'data' => array_values($allMonthsVisitsTpl)
            ];

            //所有数据
            $visitTimes = [
                "legend" => $legend,
                "months" => $allMonths,
                "visits" => $visits
            ];

            //缓存
            \Cache::store('file')->put('visitTimes:'.$lastMonth, $visitTimes, 44640);
        }

        return $visitTimes;
    }

    /**
     * 总访问人数.
     *
     * @return array
     */
    public function userLastTimes()
    {
        $nowMonth = Carbon::now()->firstOfMonth();
        $lastMonth = Carbon::now()->firstOfMonth()->subMonth();

        //判断是否有权限使用
        if(\Gate::foruser(\Auth::guard('admin')->user())->denies('statistics')){
            return \Response::json(['invalid'=>'您无权访问'])->setStatusCode(403);
        }

        //===============查询每月历史缓存是否存在，存在直接用，不存在查询该月并加入往期缓存===============
        $hasCache = \Cache::store('file')->has('userTimes:'.$lastMonth);
        //存在缓存
        if ($hasCache){
            $userTimes = \Cache::store('file')->get('userTimes:'.$lastMonth);
        }
        //不存在缓存
        else{
            //查询
            $visitTimesTpl = ModuleStatistics::where('created_at', '<', $nowMonth)->groupBy(['months','module'])
                ->orderBy('module','desc')->orderBy('months','asc')
                ->select(\DB::raw('DATE_FORMAT(created_at,\'%Y%m\') months, module, count(DISTINCT(user_id)) user'))
                ->get();

            //总访问人数
            $allPerson = ModuleStatistics::where('created_at', '<', $nowMonth)->groupBy('months')
                ->orderBy('months','asc')
                ->select(\DB::raw('DATE_FORMAT(created_at,\'%Y%m\') months, count(DISTINCT(user_id)) user'))
                ->get();

            $allMonths = $this->statistics->getAnalyseMonth();

            //初始化访问次数基础数据
            $baseData = [];
            $allMonthsVisitsTpl = [];
            foreach ($allMonths as $v){
                $baseData['Salary'][$v] = 0;
                $baseData['InsuranceProgress'][$v] = 0;
                $baseData['Insurance'][$v] = 0;
                $baseData['Compensation'][$v] = 0;
                $allMonthsVisitsTpl[$v] = 0;
            }

            //总访问人数
            foreach ($allPerson as $vm){
                $allMonthsVisitsTpl[$vm['months']] = $vm['user'];
            }

            //整理数据格式，各个模块访问次数
            foreach ($visitTimesTpl as $k=>$v) {
                switch ($v['module']) {
                    case 'Salary':
                    {
                        $baseData['Salary'][$v['months']] = $v['user'];
                        break;
                    }
                    case 'InsuranceProgress':
                    {
                        $baseData['InsuranceProgress'][$v['months']] = $v['user'];
                        break;
                    }
                    case 'Insurance':
                    {
                        $baseData['Insurance'][$v['months']] = $v['user'];
                        break;
                    }
                    case 'Compensation':
                    {
                        $baseData['Compensation'][$v['months']] = $v['user'];
                        break;
                    }
                    default :
                        break;
                }
            }

            $visits = [];
            $legend = [];
            foreach ($baseData as $k=>$v) {
                $tplCount = [];
                foreach ($v as $vv){
                    $tplCount[] = $vv;
                }

                switch ($k){
                    case 'Salary':
                    {
                        $visits[] = [
                            'name' => '薪资模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '薪资模块';
                        break;
                    }
                    case 'InsuranceProgress':
                    {
                        $visits[] = [
                            'name' => '社保进度模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '社保进度模块';
                        break;
                    }
                    case 'Insurance':
                    {
                        $visits[] = [
                            'name' => '社保模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '社保模块';
                        break;
                    }
                    case 'Compensation':
                    {
                        $visits[] = [
                            'name' => '理赔模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '理赔模块';
                        break;
                    }
                    default :
                        break;
                }

            }
            //加入总计模块
            $legend[] = '总访问人数';
            $visits[] = [
                'name' => '总访问人数',
                'data' => array_values($allMonthsVisitsTpl)
            ];

            //所有数据
            $userTimes = [
                "legend" => $legend,
                "months" => $allMonths,
                "visits" => $visits
            ];

            //缓存
            \Cache::store('file')->put('userTimes:'.$lastMonth, $userTimes, 44640);
        }

        return $userTimes;
    }

    /**
     * 当天访问次数.
     *
     * @return array
     */
    public function nowVisitTimes()
    {
        $now = Carbon::now();
        $nowMonth = Carbon::now()->firstOfMonth();
        $lastMonth = Carbon::now()->firstOfMonth()->subMonth();

        //判断是否有权限使用
        if(\Gate::foruser(\Auth::guard('admin')->user())->denies('statistics')){
            return \Response::json(['invalid'=>'您无权访问'])->setStatusCode(403);
        }

        //===============查询每月历史缓存是否存在，存在直接用，不存在查询该月并加入往期缓存===============
        $hasCache = \Cache::store('file')->has('nowVisitTimes:'.$now->format("Ymd"));
        //存在缓存
        if ($hasCache){
            $visitTimes = \Cache::store('file')->get('nowVisitTimes:'.$now->format("Ymd"));
        }
        //不存在缓存
        else{
            //查询
            $visitTimesTpl = ModuleStatistics::where('created_at', '>=', $nowMonth)
                ->where('created_at', '<', $now->format('Y-m-d 00:00:00'))
                ->groupBy(['days','module'])
                ->orderBy('module','desc')->orderBy('days','asc')
                ->select(\DB::raw('DATE_FORMAT(created_at,\'%Y%m%d\') days, module, count(id) count'))
                ->get();

            $allDays = $this->statistics->getAnalyseDay($now);

            //初始化访问次数基础数据
            $baseData = [];
            $allDaysVisitsTpl = [];
            foreach ($allDays as $v){
                $baseData['Salary'][$v] = 0;
                $baseData['InsuranceProgress'][$v] = 0;
                $baseData['Insurance'][$v] = 0;
                $baseData['Compensation'][$v] = 0;
                $allDaysVisitsTpl[$v] = 0;
            }

            //整理数据格式，各个模块访问次数
            foreach ($visitTimesTpl as $k=>$v) {
                $allDaysVisitsTpl[$v['days']] += $v['count'];
                switch ($v['module']) {
                    case 'Salary':
                    {
                        $baseData['Salary'][$v['days']] = $v['count'];
                        break;
                    }
                    case 'InsuranceProgress':
                    {
                        $baseData['InsuranceProgress'][$v['days']] = $v['count'];
                        break;
                    }
                    case 'Insurance':
                    {
                        $baseData['Insurance'][$v['days']] = $v['count'];
                        break;
                    }
                    case 'Compensation':
                    {
                        $baseData['Compensation'][$v['days']] = $v['count'];
                        break;
                    }
                    default :
                        break;
                }
            }

            $visits = [];
            $legend = [];

            foreach ($baseData as $k=>$v) {
                $tplCount = [];
                foreach ($v as $vv){
                    $tplCount[] = $vv;
                }

                switch ($k){
                    case 'Salary':
                    {
                        $visits[] = [
                            'name' => '薪资模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '薪资模块';
                        break;
                    }
                    case 'InsuranceProgress':
                    {
                        $visits[] = [
                            'name' => '社保进度模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '社保进度模块';
                        break;
                    }
                    case 'Insurance':
                    {
                        $visits[] = [
                            'name' => '社保模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '社保模块';
                        break;
                    }
                    case 'Compensation':
                    {
                        $visits[] = [
                            'name' => '理赔模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '理赔模块';
                        break;
                    }
                    default :
                        break;
                }

            }

            //加入总计模块
            $legend[] = '总访问次数';
            $visits[] = [
                'name' => '总访问次数',
                'data' => array_values($allDaysVisitsTpl)
            ];

            //所有数据
            $visitTimes = [
                "legend" => $legend,
                "days" => $allDays,
                "visits" => $visits
            ];

            //缓存
            \Cache::store('file')->put('nowVisitTimes:'.$now->format("Ymd"), $visitTimes, 1440);
        }

        return $visitTimes;
    }

    /**
     * 当天访问人数.
     *
     * @return array
     */
    public function nowUserTimes()
    {
        $now = Carbon::now();
        $nowMonth = Carbon::now()->firstOfMonth();
        $lastMonth = Carbon::now()->firstOfMonth()->subMonth();

        //判断是否有权限使用
        if(\Gate::foruser(\Auth::guard('admin')->user())->denies('statistics')){
            return \Response::json(['invalid'=>'您无权访问'])->setStatusCode(403);
        }

        //===============查询每月历史缓存是否存在，存在直接用，不存在查询该月并加入往期缓存===============
        $hasCache = \Cache::store('file')->has('nowUserTimes:'.$now->format("Ymd"));
        //存在缓存
        if ($hasCache){
            $userTimes = \Cache::store('file')->get('nowUserTimes:'.$now->format("Ymd"));
        }
        //不存在缓存
        else{
            //查询
            $visitTimesTpl = ModuleStatistics::where('created_at', '>=', $nowMonth)
                ->where('created_at', '<', $now->format('Y-m-d 00:00:00'))
                ->groupBy(['days','module'])
                ->orderBy('module','desc')->orderBy('days','asc')
                ->select(\DB::raw('DATE_FORMAT(created_at,\'%Y%m%d\') days, module, count(DISTINCT(user_id)) user'))
                ->get();

            //总访问人数
            $allPerson = ModuleStatistics::where('created_at', '>=', $nowMonth)
                ->where('created_at', '<', $now->format('Y-m-d 00:00:00'))
                ->groupBy('days')
                ->orderBy('days','asc')
                ->select(\DB::raw('DATE_FORMAT(created_at,\'%Y%m%d\') days, count(DISTINCT(user_id)) user'))
                ->get();

            $allDays = $this->statistics->getAnalyseDay($now);

            //初始化访问次数基础数据
            $baseData = [];
            $allDaysVisitsTpl = [];
            foreach ($allDays as $v){
                $baseData['Salary'][$v] = 0;
                $baseData['InsuranceProgress'][$v] = 0;
                $baseData['Insurance'][$v] = 0;
                $baseData['Compensation'][$v] = 0;
                $allDaysVisitsTpl[$v] = 0;
            }

            //总访问人数
            foreach ($allPerson as $vm){
                $allDaysVisitsTpl[$vm['days']] = $vm['user'];
            }

            //整理数据格式，各个模块访问次数
            foreach ($visitTimesTpl as $k=>$v) {
                switch ($v['module']) {
                    case 'Salary':
                    {
                        $baseData['Salary'][$v['days']] = $v['user'];
                        break;
                    }
                    case 'InsuranceProgress':
                    {
                        $baseData['InsuranceProgress'][$v['days']] = $v['user'];
                        break;
                    }
                    case 'Insurance':
                    {
                        $baseData['Insurance'][$v['days']] = $v['user'];
                        break;
                    }
                    case 'Compensation':
                    {
                        $baseData['Compensation'][$v['days']] = $v['user'];
                        break;
                    }
                    default :
                        break;
                }
            }

            $visits = [];
            $legend = [];
            foreach ($baseData as $k=>$v) {
                $tplCount = [];
                foreach ($v as $vv){
                    $tplCount[] = $vv;
                }

                switch ($k){
                    case 'Salary':
                    {
                        $visits[] = [
                            'name' => '薪资模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '薪资模块';
                        break;
                    }
                    case 'InsuranceProgress':
                    {
                        $visits[] = [
                            'name' => '社保进度模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '社保进度模块';
                        break;
                    }
                    case 'Insurance':
                    {
                        $visits[] = [
                            'name' => '社保模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '社保模块';
                        break;
                    }
                    case 'Compensation':
                    {
                        $visits[] = [
                            'name' => '理赔模块',
                            'data' => $tplCount
                        ];
                        $legend[] = '理赔模块';
                        break;
                    }
                    default :
                        break;
                }

            }
            //加入总计模块
            $legend[] = '总访问人数';
            $visits[] = [
                'name' => '总访问人数',
                'data' => array_values($allDaysVisitsTpl)
            ];

            //所有数据
            $userTimes = [
                "legend" => $legend,
                "days" => $allDays,
                "visits" => $visits
            ];

            //缓存
            \Cache::store('file')->put('nowUserTimes:'.$now->format("Ymd"), $userTimes, 1440);
        }

        return $userTimes;
    }
}
