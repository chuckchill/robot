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
    public static function getTypeTree($forceHtml = false, $type = '')
    {
        $mediaType = Cache::store('file')->rememberForever('videotype', function () {
            return \App\Models\Common\MediaType::orderBy('pid', SORT_DESC)->get();
        });
        if ($type) {
            $mediaType = $mediaType->where('type', $type);
        }
        $data = $mediaType->toArray();
        foreach ($data as $key => $type) {
            $data[$key]['thumb'] = self::getThumbImg($type['id']);
        }
        $config = [
            'primary_key' => 'id',
            'parent_key' => 'pid',
        ];
        if ($forceHtml) {
            return PHPTreeClass::makeTreeForHtml($data, $config);
        }
        return PHPTreeClass::makeTree($data, $config);;
    }

    protected static function getThumbImg($id)
    {
        $path = "/upload/mediatype/" . $id . ".jpg";
        if (file_exists(public_path($path))){
            return url($path);
    }
        return url("/images/video.jpg");
    }
}