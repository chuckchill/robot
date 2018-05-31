<?php
namespace App\Facades;
use Illuminate\Support\Facades\Facade;

/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2017/9/22
 * Time: 14:32
 */
class Logger extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'logger';
    }
}