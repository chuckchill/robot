<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/9/25
 * Time: 20:14
 */

namespace App\Models\Common;


use Illuminate\Database\Eloquent\Model;

class FeedBack extends Model
{
    protected $table = 'feedback';
    protected $dateFormat = "Y-m-d H:i:s";
}