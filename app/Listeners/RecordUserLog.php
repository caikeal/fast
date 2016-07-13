<?php

namespace App\Listeners;

use App\Events\UserLog;
use App\ModuleStatistics;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordUserLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserLog  $event
     * @return void
     */
    public function handle(UserLog $event)
    {
        //60秒钟内重复访问无效
        $now = Carbon::now()->subSeconds(60);

        $tplData = ModuleStatistics::where('user_id', $event->moduleData->user_id)
            ->where('ip', $event->moduleData->ip)
            ->where('module', $event->moduleData->module)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$tplData || ($tplData && $tplData->created_at < $now->toDateTimeString())){
            $event->moduleData->save();
        }
    }
}
