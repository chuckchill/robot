<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/7/6
 * Time: 13:18
 */

namespace App\Services\ModelService;


use App\Models\Common\ArticleContent;
use App\Services\Helper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Class Article
 * @package App\Services\ModelService
 */
class Article
{
    /**
     * @param $articleId
     */
    public static function saveContent($articleId, $content)
    {
        $path = "article/" . ($articleId % 10) . "/";
        Storage::put($path . $articleId, $content);
        return $path;
    }

    /**
     * @param $articleId
     * @return string
     */
    public static function getContent($articleId)
    {
        $path = "article/" . ($articleId % 10) . "/" . $articleId;
        if (Storage::exists($path)) {
            return Storage::get($path);;
        }
        return "";
    }
}