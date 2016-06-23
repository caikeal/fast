<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\SalaryDetail;
use App\SalaryTask;
use App\User;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use DB;

class SaveUploadSalary extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $base_id;
    protected $company_id;
    protected $task_id;
    protected $type;
    protected $manager_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($base_id, $company_id, $task_id, $type, $manager_id)
    {
        $this->base_id = $base_id;
        $this->company_id = $company_id;
        $this->task_id = $task_id;
        $this->type = $type;
        $this->manager_id = $manager_id;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        DB::reconnect();
        $now = Carbon::now();
        $contents=Cache::store('file')->get('admin_salaryUp:' . $this->base_id . "|" . $this->company_id."|".$this->task_id);
        //开启事务
        DB::beginTransaction();
        try {
            $all_content = json_decode($contents,true);
            foreach ($all_content as $k => $v) {
                if ($k > 0 && $v[0]) {
                    $v1_type = is_string($v[1]) ? $v[1] : sprintf('%0.0f', $v[1]);
                    $is_exist_user = User::where("id_card", "=", $v1_type)->first();
                    $is_exist_detail = "";
                    if ($is_exist_user) {
                        $is_exist_detail = SalaryDetail::where("user_id", "=", $is_exist_user->id)
                            ->where("company_id", "=", $this->company_id)
                            ->where("salary_day", "=", $v[2])
                            ->where("type", $this->type)
                            ->first();
                    }

                    //用户创建
                    if (!$is_exist_user) {
                        $user_id = DB::table('users')->insertGetId([
                            'name' => $v[0],
                            'id_card' => $v1_type,
                            'manager_id' => $this->manager_id,
                            'company_id' => $this->company_id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    } else {
                        $user_id = $is_exist_user->id;
                    }
                    //薪资数据保存
                    $wages = "";
                    foreach ($v as $kk => $vv) {
                        if ($kk > 2) {
                            //由于是日期格式excel会以数组返回
                            if(is_array($vv)){
                                $vv=date("Y/m/d",strtotime($vv['date']));
                            }
                            $wages .= $vv . "||";
                        }
                    }
                    $wages = trim($wages, "||");
                    if (!$is_exist_detail) {
                        DB::table('salary_details')->insert([
                            'user_id' => $user_id,
                            'base_id' => $this->base_id,
                            'company_id' => $this->company_id,
                            'wages' => $wages,
                            'salary_day' => $v[2],
                            'manager_id' => $this->manager_id,
                            'type' => $this->type,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    } else {
                        DB::table('salary_details')->where('company_id', "=", $this->company_id)
                            ->where('salary_day', "=", $v[2])
                            ->where('user_id', "=", $user_id)->update([
                                'base_id' => $this->base_id,
                                'wages' => $wages,
                                'manager_id' => $this->manager_id,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ]);
                    }
                }
            }
            //把大内存的数据即使清空
            unset($all_content);

            //设置任务已提交成功
            DB::table('salary_task')->where("company_id", "=", $this->company_id)->where("type", $this->type)
                ->where("receive_id", "=", $this->manager_id)->where("id", "=", $this->task_id)
                ->update(["status" => 1,"updated_at"=>$now]);

            //提交事务
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::disconnect();
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
