<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/8/14
 * Time: 20:16
 */

namespace App\Models\Api;


use Illuminate\Database\Eloquent\Model;

class AppusersContacts extends Model
{
    protected $table = 'app_users_contacts';
    protected $dateFormat = "Y-m-d H:i:s";
}