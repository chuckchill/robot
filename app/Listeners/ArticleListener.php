<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/7/6
 * Time: 13:30
 */

namespace App\Listeners;


use App\Events\ArticleEvent;
use Illuminate\Support\Facades\Cache;

class ArticleListener
{

    /**
     * Handle the event.
     *
     * @param  ArticleEvent $event
     * @return void
     */
    public function handle(ArticleEvent $event)
    {
        Cache::store('file')->forget('article_content_'.$event->articleId);//清理缓存
    }
}