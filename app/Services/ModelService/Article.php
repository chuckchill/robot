<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/7/6
 * Time: 13:18
 */

namespace App\Services\ModelService;


use App\Models\Common\ArticleContent;
use Illuminate\Support\Facades\Cache;

class Article
{
    public static function getContent($articleId)
    {
        $videoType = Cache::store('file')->rememberForever('article_content_' . $articleId, function () use ($articleId) {
            return ArticleContent::where(['article_id' => $articleId])->first();
        });

        return $videoType ? $videoType->content : "";
    }
}