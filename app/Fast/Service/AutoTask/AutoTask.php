<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/7/11
 * Time: 15:43
 */

namespace App\Fast\Service\AutoTask;


class AutoTask
{
    /**
     * 判断自动创建任务能否创建.
     *
     * @param $creator
     * @param $receiver
     * @param $by
     * @param $company_id
     * @param $type
     * @return bool
     */
    public function isEffective($creator, $receiver, $by, $company_id, $type){
        $hasIt = \App\AutoTask::where('creator', $creator)
            ->where('receiver', $receiver)
            ->where(function($query) use ($receiver,$by){
                $query->where('by', $by)->orWhere('by', $receiver);
            })
            ->where('company_id', $company_id)
            ->where('type', $type)->count();

        return $hasIt?false:true;
    }

    /**
     * 创建自动任务.
     *
     * @param $creator
     * @param $receiver
     * @param $by
     * @param $company_id
     * @param $type
     * @param $deal_time
     * @param $memo
     * @return bool
     */
    public function storeAutoTask($creator, $receiver, $by, $company_id, $type, $deal_time, $memo)
    {
        if ($this->isEffective($creator, $receiver, $by, $company_id, $type)){
            $newTask = new \App\AutoTask();
            $newTask->creator = $creator;
            $newTask->receiver = $receiver;
            $newTask->company_id = $company_id;
            $newTask->type = $type;
            $newTask->deal_time = $deal_time;
            $newTask->memo = $memo;
            $newTask->save();
            return true;
        }else{
            return false;
        }
    }

    /**
     * 删除自动任务.
     *
     * @param $creator
     * @param $receiver
     * @param $by
     * @param $company_id
     * @param $type
     * @return bool
     */
    public function deleteAutoTask($creator, $receiver, $by, $company_id, $type)
    {
        if (!$this->isEffective($creator, $receiver, $by, $company_id, $type)){
            $newTask = \App\AutoTask::where('creator', $creator)
                ->where('receiver', $receiver)
                ->where(function($query) use ($receiver,$by){
                    $query->where('by', $by)->orWhere('by', $receiver);
                })
                ->where('company_id', $company_id)
                ->where('type', $type)->delete();
            return true;
        }else{
            return false;
        }
    }

    /**
     * 修改自动任务内容.
     *
     * @param array $old
     * @param $deal_time
     * @param $receiver
     * @param $by
     * @param $memo
     * @return bool
     */
    public function changeAutoTask(array $old, $receiver, $by, $deal_time, $memo)
    {
        //原先的任务是否存在
        if (!$this->isEffective($old['creator'], $old['receiver'], $old['by'], $old['company_id'], $old['type'])){
            //修改后的任务是否存在
            if ($this->isEffective($old['creator'], $receiver, $by, $old['company_id'], $old['type'])){
                $autoTask = \App\AutoTask::where('creator', $old['creator'])
                    ->where('receiver', $old['receiver'])
                    ->where(function($query) use ($old){
                        $query->where('by', $old['by'])->orWhere('by', $old['receiver']);
                    })
                    ->where('company_id', $old['company_id'])
                    ->where('type', $old['type'])->first();

                if ($autoTask) {
                    $autoTask->receiver = $receiver;
                    $autoTask->by = $by;
                    $autoTask->deal_time = $deal_time;
                    $autoTask->memo = $memo;
                    $autoTask->update();
                }
                return true;
            }else{
                //存在删除原先的自动任务
                $this->deleteAutoTask($old['creator'], $old['receiver'], $old['by'], $old['company_id'], $old['type']);
            }
        }

        return false;
    }
}