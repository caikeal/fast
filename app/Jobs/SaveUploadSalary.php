<?php

namespace App\Jobs;

use App\Fast\Service\Salary\Salary;
use App\SalaryTask;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SaveUploadSalary extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $base_id;
    protected $company_id;
    protected $task_id;
    protected $type;
    protected $manager_id;
    protected $salary;

    /**
     * SaveUploadSalary constructor.
     * @param $base_id
     * @param $company_id
     * @param $task_id
     * @param $type
     * @param $manager_id
     */
    public function __construct($base_id, $company_id, $task_id, $type, $manager_id)
    {
        $this->base_id = $base_id;
        $this->company_id = $company_id;
        $this->task_id = $task_id;
        $this->type = $type;
        $this->manager_id = $manager_id;
        $this->salary = new Salary();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $now = Carbon::now();
        $contents = Cache::store('file')
            ->get('admin_salaryUp:' . $this->base_id . "|" . $this->company_id."|".$this->task_id);
        //开启事务
        DB::beginTransaction();
        try {
            $all_content = json_decode($contents,true);

            //保存薪资数据
            $this->salary->storeSalary($this->base_id,$this->company_id,$this->type,$this->manager_id,$all_content);
            //把大内存的数据即使清空
            unset($all_content);

            //设置任务已提交成功
            DB::table('salary_task')->where("company_id", "=", $this->company_id)->where("type", $this->type)
                ->where("receive_id", "=", $this->manager_id)->where("id", "=", $this->task_id)
                ->update(["status" => 1, "updated_at"=>$now]);

            //提交事务
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    /**
     * 处理失败任务
     *
     * @return void
     */
    public function failed()
    {
        SalaryTask::where("company_id", "=", $this->company_id)->where("type", $this->type)
            ->where("receive_id", "=", $this->manager_id)->where("id", "=", $this->task_id)
            ->update(["status" => 0]);
    }
}
