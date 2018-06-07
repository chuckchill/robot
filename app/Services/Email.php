<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/7
 * Time: 17:55
 */

namespace App\Services;


use App\Exceptions\CodeException;
use Dingo\Api\Console\Command\Cache;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class Email
{
    public static function sendMsg($email, $msg, $isText = true)
    {
        try {
            if ($isText) {
                Mail::raw($msg, function ($message) use ($email) {
                    $message->from("admin@robot.com", 'robot');
                    $message->to('$email');
                });
            } else {
                Mail::send("welcome", [], function () {

                });
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function SendRandCode($email)
    {
        $code = rand(1000, 9999);
        $cacheKey = "mailCode_" . md5($email);
        if (Cache::get($cacheKey)) {
            throw new CodeException(config('code.reg.code_30_valid'));
        }
        if (self::sendMsg($email, $code)) {
            Cache::put("mailCode_" . md5($email), $code, 60 * 30);
        }
        throw new CodeException(config('code.reg.email_send_error'));
        return true;
    }

    public static function validateCode($email, $code)
    {
        $cacheKey = "mailCode_" . md5($email);
        if (Cache::get($cacheKey) != $code) {
            throw new CodeException(config('code.reg.emailcode_error'));
        }
        return true;
    }
}