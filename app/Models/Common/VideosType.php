<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/26
 * Time: 14:52
 */

namespace App\Models\Common;


use Illuminate\Database\Eloquent\Model;

class VideosType extends Model
{
    protected $table = 'videos_type';

    protected $dateFormat = "Y-m-d H:i:s";
}