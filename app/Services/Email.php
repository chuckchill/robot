<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/7
 * Time: 17:55
 */

namespace App\Services;


use App\Exceptions\CodeException;
use App\Facades\Logger;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class Email
{
    /**
     * @var string
     */
    public $emailLoginKey = '_email_login';
    /**
     * @var string
     */
    public $email;

    /**
     * Sms constructor.
     * @param string $phone
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    public function sendMsg($email, $msg, $isText = true)
    {
        try {
            if ($isText) {
                Mail::raw($msg, function ($message) use ($email) {
                    $message->from("admin@robot.com", 'robot');
                    $message->to($email);
                });
            } else {
                Mail::send("welcome", [], function () {

                });
            }
        } catch (\Exception $e) {
            Logger::info($e->getMessage(), "email");
            throw new CodeException(config('code.reg.email_send_error'));
        }
    }

    public function sendRandCode()
    {
        $code = rand(1000, 9999);
        $cacheKey = md5($this->email) . $this->emailLoginKey;
        if (Cache::get($cacheKey)) {
            throw new CodeException(config('code.reg.code_30_valid'));
        }
        if (!Helper::mustSendEmail()) {
            Cache::put($cacheKey, "123456", 30);
            return true;
        }
        self::sendMsg($this->email, $code);
        Cache::put($cacheKey, $code, 30);
        Logger::info("往{$this->email}发送验证码：" . $code);
    }

    public function validateCode($code)
    {
        $cacheKey = md5($this->email) . $this->emailLoginKey;
        if ($code == "" || Cache::get($cacheKey) != $code) {
            throw new CodeException(config('code.reg.emailcode_error'));
        }
    }
}