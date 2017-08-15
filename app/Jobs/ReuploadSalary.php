<?php

namespace App\Jobs;

use App\Fast\Service\Salary\Salary;
use App\ReuploadApplication;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReuploadSalary extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $base_id;
    protected $company_id;
    protected $reupload_id;
    protected $type;
    protected $manager_id;
    protected $upload_id;
    protected $salary;

    /**
     * ReuploadSalary constructor.
     *
     * @param $base_id
     * @param $company_id
     * @param $reupload_id
     * @param $type
     * @param $manager_id
     */
    public function __construct($base_id, $company_id, $reupload_id, $type, $manager_id, $upload_id)
    {
        $this->base_id = $base_id;
        $this->company_id = $company_id;
        $this->reupload_id = $reupload_id;
        $this->type = $type;
        $this->manager_id = $manager_id;
        $this->upload_id = $upload_id;
        $this->salary = new Salary();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $contents = Cache::store('file')
            ->get('admin_salaryReUp:' . $this->base_id . "|" . $this->company_id."|".$this->reupload_id."|".$this->upload_id);
        //开启事务
        DB::beginTransaction();
        try {
            $all_content = json_decode($contents,true);

            //保存薪资数据
            $this->salary->storeSalary($this->base_id,$this->company_id,$this->type,$this->manager_id,$all_content);
            //把大内存的数据即使清空
            unset($all_content);

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
        //关闭申请
        if ($this->reupload_id){
            ReuploadApplication::where('id', $this->reupload_id)->update(["status" => 1]);
        }
    }
}
