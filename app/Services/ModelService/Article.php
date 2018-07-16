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

        $path = self::getFilePath($articleId);
        if ($content) {
            Storage::put($path, $outstr = iconv('GBK', 'UTF-8', $content));
        }
        return $path;
    }

    /**
     * @param $articleId
     * @return string
     */
    public static function getContent($articleId)
    {
        $path = self::getFilePath($articleId);
        if (Storage::exists($path)) {
            return Storage::get($path);
        }
        return "";
    }

    public static function deleteContent($articleId)
    {
        $path = self::getFilePath($articleId);
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }

    public static function getFilePath($articleId)
    {
        return "article/" . ($articleId % 10) . "/" . $articleId . ".ar";
    }

    public static function getWordPath($articleId)
    {
        return "/upload/article/word/" . ($articleId % 10) . "/";
    }
}