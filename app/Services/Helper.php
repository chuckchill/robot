<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/7
 * Time: 13:56
 */

namespace App\Services;


class Helper
{
    public static function mkDir($dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        return $dir;
    }
}