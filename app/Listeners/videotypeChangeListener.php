<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/6/27
 * Time: 20:48
 */

namespace App\Listeners;


use App\Events\videotypeChangeEvent;
use Illuminate\Support\Facades\Cache;

class videotypeChangeListener
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
     * @param  permChangeEvent $event
     * @return void
     */
    public function handle(videotypeChangeEvent $event)
    {
        Cache::store('file')->forget('videotype');//清理缓存
    }
}