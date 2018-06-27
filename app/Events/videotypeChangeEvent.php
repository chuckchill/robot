<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/6/27
 * Time: 20:47
 */

namespace App\Events;


use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class videotypeChangeEvent extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

}