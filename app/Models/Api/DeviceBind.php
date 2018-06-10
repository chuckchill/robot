<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/6/9
 * Time: 12:52
 */

namespace App\Models\Api;


use Illuminate\Database\Eloquent\Model;

class DeviceBind extends Model
{
    protected $table = 'devices_bind';
    protected $dateFormat = "Y-m-d H:i:s";
}