<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/7
 * Time: 13:56
 */

namespace App\Services;


class Helper
{
    public static function mkDir($dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        return $dir;
    }

    public static function isUserName($user)
    {
        if (preg_match("/^[a-zA-Z]{1}([a-zA-Z0-9]){5,9}$/", $user)) {
            return true;
        } else {
            return false;
        }
    }

    public static function isPassword($password)
    {
        if (preg_match("/^[a-zA-Z0-9]{6,16}$/", $password)) {
            return true;
        } else {
            return false;
        }
    }

    public static function mustSendSms()
    {
        return config('other.email_action');
    }

    public static function mustSendEmail()
    {
        return config('other.mobile_action');
    }
}