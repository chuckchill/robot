<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/7/6
 * Time: 13:30
 */

namespace App\Events;


use Illuminate\Queue\SerializesModels;

class ArticleEvent extends Event
{
    use SerializesModels;
    public $articleId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($articleId)
    {
        $this->articleId = $articleId;
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