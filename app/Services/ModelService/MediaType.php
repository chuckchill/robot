<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/26
 * Time: 14:55
 */

namespace App\Services\ModelService;


use App\Services\PHPTreeClass;
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
    public static function getTypeTree($forceHtml = false)
    {
        $mediaType = Cache::store('file')->rememberForever('videotype', function () {
            return \App\Models\Common\MediaType::orderBy('pid', SORT_DESC)->get();
        });
        $config = [
            'primary_key' => 'id',
            'parent_key' => 'pid',
        ];
        if ($forceHtml) {
            return PHPTreeClass::makeTreeForHtml($mediaType->toArray(), $config);
        }
        return PHPTreeClass::makeTree($mediaType->toArray(), $config);;
    }

}