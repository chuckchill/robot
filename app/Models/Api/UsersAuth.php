<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/7
 * Time: 15:45
 */

namespace App\Models\Api;


use Illuminate\Database\Eloquent\Model;

class UsersAuth extends Model
{
    protected $table = 'app_users_auth';
    protected $dateFormat = "Y-m-d H:i:s";
}