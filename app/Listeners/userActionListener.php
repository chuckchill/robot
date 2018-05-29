<?php

namespace App\Listeners;

use App\Models\Admin\AdminLog;
use App\Events\userActionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class userActionListener implements ShouldQueue
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
     * @param  userActionEvent $event
     * @return void
     */
    public function handle(userActionEvent $event)
    {
        $str = '管理员:' . $event->adminName . '(id:' . $event->uid . ')' . $event->content;
        //Log::info($str);
        $log=new AdminLog();
        $log->uid=$event->uid;
        $log->name=$event->adminName;
        $log->model=$event->model;
        $log->aid = $event->aid;
        $log->type= $event->type;
        $log->remarks = $str;
        $log->save();
    }
}
