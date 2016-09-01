<?php

namespace App\Console;

use App\AutoTask;
use App\Fast\Service\CrontabList\AutoMultiTask;
use App\Fast\Service\Task\Task;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
         Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->call(function(){
             $now = Carbon::now();
             $nextMonthDay = $now->addMonth();
             $nowTime = strtotime(($nextMonthDay->format("Y-m"))."-1");
             //取出适当的自动任务
             $allNeedTask = AutoTask::where('deal_time',"<",$nowTime)->get();
             $task = new Task();
             $multiTask = new AutoMultiTask();
             foreach ($allNeedTask as $k=>$v){
                 $multiTask->createMulti($v, $task);
             }
         })->monthly();

        $schedule->call(function(){
            AutoTask::where('id', 1)->update(['memo' => 'kealTest']);
        })->everyMinute();
    }
}
