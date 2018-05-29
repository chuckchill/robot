<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/5/29
 * Time: 17:23
 */

namespace App\Models\Admin;


use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $table = "admin_log";
    protected $dateFormat = "Y-m-d H:i:s";
}