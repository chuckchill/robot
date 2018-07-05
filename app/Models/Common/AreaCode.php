<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/7/5
 * Time: 10:41
 */

namespace App\Models\Common;


use Illuminate\Database\Eloquent\Model;

class AreaCode extends Model
{
    protected $table = 'area_code';

    protected $dateFormat = "Y-m-d H:i:s";
}