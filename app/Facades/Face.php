<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/12/4
 * Time: 15:05
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class Face extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'face';
    }
}