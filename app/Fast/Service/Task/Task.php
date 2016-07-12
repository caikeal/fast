<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/7/11
 * Time: 16:34
 */

namespace App\Fast\Service\Task;


use App\SalaryTask;
use Carbon\Carbon;

class Task
{
    /**
     * 判断薪资、社保任务是否已经建过
     * 建立规则同人，同月，同企业，同类型不能重复
     *
     * @param $creator
     * @param $receiver
     * @param $by
     * @param $company_id
     * @param $type
     * @param $salary_day
     * @return bool
     */
    public function isRepeat($creator, $receiver, $by, $company_id, $type, $salary_day)
    {
        $hasIt = SalaryTask::where('manager_id', $creator)
            ->where('receive_id', $receiver)
            ->where(function($query) use ($by, $receiver){
                $query->where('by_id', $by)->orWhere('by_id', $receiver);
            })
            ->where('company_id', $company_id)
            ->where('type', $type)->where('salary_day', $salary_day)->count();

        return $hasIt ? true : false;
    }

    /**
     * 保存薪资、社保任务
     *
     * @param $creator
     * @param $receiver
     * @param $by
     * @param $company_id
     * @param $type
     * @param $deal_time
     * @param $memo
     * @return SalaryTask
     */
    public function storeTask($creator, $receiver, $by, $company_id, $type, $deal_time, $memo)
    {
        $task = new SalaryTask();
        $task->manager_id = $creator;
        $task->company_id = $company_id;
        $task->receive_id = $receiver;
        $task->by_id = $by;
        $task->type = $type;
        $task->memo = $memo;
        $task->status = 0;
        $task->deal_time = $deal_time;
        $task->salary_day = date("Ym", $deal_time);
        $task->save();

        return $task;
    }

    public function deleteTask($id)
    {
        $isDelete = SalaryTask::where('id', $id)->delete($id);

        return $isDelete ? true : false;
    }
}