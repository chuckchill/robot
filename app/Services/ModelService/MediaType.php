<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/26
 * Time: 14:55
 */

namespace App\Services\ModelService;


use Illuminate\Support\Facades\Cache;

/**
 * Class MediaType
 * @package App\Services\ModelService
 */
class MediaType
{
    /**
     * @param bool $filter
     * @return array
     */
    public static function getTypeTree($filter = false)
    {
        $videoType = Cache::store('file')->rememberForever('videotype', function () {
            return \App\Models\Common\MediaType::orderBy('pid', SORT_DESC)->get();
        });
        $result = [];
        foreach ($videoType as $item) {
            if ($item->pid == 0) {
                $result[$item->id]["id"] = $item->id;
                $result[$item->id]["name"] = $item->name;
            } else {
                $result[$item->pid]['children'][] = ["id" => $item->id, "name" => $item->name];
            }
        }
        if ($filter) {//过滤没有子菜单的项目
            $result = array_filter($result, function ($item) {
                return isset($item['children']);
            });
        }
        return $result;
    }

}