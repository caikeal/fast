<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/7/12
 * Time: 10:31
 */

namespace App\Fast\Service\CrontabList;


use App\Fast\Service\Task\Task;
use Carbon\Carbon;

class AutoMultiTask
{
    public function createMulti($taskItem, Task $task)
    {
        $salary_day = "";
        $now_day_stamp=strtotime(date("Y-m", time())."-1 0:0:0");
        if ($now_day_stamp > $taskItem['deal_time']){
            $days = $now_day_stamp;
        }else{
            $days = $taskItem['deal_time'];
        }
        $specificDay = date("d", $taskItem['deal_time']);

        //判断当月任务是否重复创建
        $saveDay = strtotime(Carbon::createFromTimestamp($days)->format("Y-m").$specificDay." 0:0:0");
        $salary_day = Carbon::createFromTimestamp($days)->format("Ym");
        $noNeed = $task->isRepeat($taskItem['creator'], $taskItem['receiver'], $taskItem['by'], $taskItem['company_id'], $taskItem['type'], $salary_day);
        //创建任务
        if (!$noNeed){
            $task->storeTask($taskItem['creator'], $taskItem['receiver'], $taskItem['by'], $taskItem['company_id'], $taskItem['type'], $saveDay, $taskItem['memo']);
        }

        //判断下月任务是否重复创建
        $saveDay = strtotime(Carbon::createFromTimestamp($days)->addMonth()->format("Y-m")."-".$specificDay." 0:0:0");
        $salary_day = Carbon::createFromTimestamp($days)->addMonth()->format("Ym");
        $noNeed = $task->isRepeat($taskItem['creator'], $taskItem['receiver'], $taskItem['by'], $taskItem['company_id'], $taskItem['type'], $salary_day);
        //创建任务
        if (!$noNeed){
            $task->storeTask($taskItem['creator'], $taskItem['receiver'], $taskItem['by'], $taskItem['company_id'], $taskItem['type'], $saveDay, $taskItem['memo']);
        }

        return true;
    }
}