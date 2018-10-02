<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/7/5
 * Time: 10:59
 */

namespace App\Services\ModelService;


use Illuminate\Support\Facades\Cache;

class AreaCode
{
    public static function getCityTree()
    {
        $areaCode = Cache::store('file')->rememberForever('city_code', function () {
            return \App\Models\Common\AreaCode::whereIn('arealevel', [1, 2, 3])->get();
        });
        $result = [];
        foreach ($areaCode as $key => $area) {
            if ($area->arealevel == 1) {
                $result[$area->code]["code"] = $area->code;
                $result[$area->code]["name"] = $area->name;
            } else {
                $result[$area->parent_code]['children'][$area->code] = ["code" => $area->code, "name" => $area->name];
            }
        }
        return $result;
    }
}