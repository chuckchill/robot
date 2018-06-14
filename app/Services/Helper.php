<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/7
 * Time: 13:56
 */

namespace App\Services;


use App\Exceptions\CodeException;
use App\Facades\Logger;

class Helper
{
    /**
     * @param $dirpath
     * @return bool
     */
    public static function mkDir($dirpath)
    {
        if (!file_exists($dirpath)) {
            $old_mask = umask(0);
            if (!mkdir($dirpath, 0777, true)) {
                Logger::info("创建" . date('Y-m-d', time()) . "日志目录失败", "other");
                return false;
            }
            umask($old_mask);
        }
        return $dirpath;
    }

    /**
     * @param $user
     * @return bool
     */
    public static function isUserName($user)
    {
        if (preg_match("/^[a-zA-Z]{1}([a-zA-Z0-9]){5,9}$/", $user)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $password
     * @return bool
     */
    public static function isPassword($password)
    {
        if (preg_match("/^[a-zA-Z0-9]{6,16}$/", $password)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public static function mustSendSms()
    {
        return config('other.email_action');
    }

    /**
     * @return mixed
     */
    public static function mustSendEmail()
    {
        return config('other.mobile_action');
    }

    /**
     * @param $codes
     * @throws CodeException
     */
    public function codeException($postfix)
    {
        throw new CodeException(config($postfix));
    }
}