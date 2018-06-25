<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/25
 * Time: 15:41
 */

namespace App\Models\Common;


use Illuminate\Database\Eloquent\Model;

class LiveVideos extends Model
{
    protected $table = "live_videos";
    protected $dateFormat = "Y-m-d H:i:s";
}