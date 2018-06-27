<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/26
 * Time: 14:55
 */

namespace App\Services\ModelService;


use Illuminate\Support\Facades\Cache;

class VideosType
{
    public static function getTypeTree()
    {
        $videoType = Cache::store('file')->rememberForever('videotype', function () {
            return \App\Models\Common\VideosType::orderBy('pid', SORT_DESC)->get();
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
        return $result;
    }

}