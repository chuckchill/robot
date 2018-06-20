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

/**
 * Class Helper
 * @package App\Services
 */
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
     * @param $password
     * @return bool
     */
    public static function isMobile($mobile)
    {
        if (preg_match("/^1[3|4|5|6|7|8|9]\d{9}$/", $mobile)) {
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

    /**
     * @param $key
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public static function getVideoThumb($key)
    {
        $saveKey = md5($key);
        $thumbPath = 'upload/video_thumb';
        $thumbPath = self::mkDir($thumbPath);
        $dirPath = public_path($thumbPath . "/" . $saveKey . ".jpg");
        if (!file_exists($dirPath)) {
            $qn = new Qiniu();
            $url = $qn->getVideoThumb($key);
            $content = file_get_contents($url);
            file_put_contents($dirPath, $content);
        }
        return url($thumbPath . "/" . $saveKey . ".jpg");
    }
}