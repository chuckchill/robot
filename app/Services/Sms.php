<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2017/9/22
 * Time: 13:45
 */

namespace App\Services;


use App\Exceptions\CodeException;
use App\Facades\Logger;
use Illuminate\Support\Facades\Cache;

/**
 * Class Sms
 * @package App\Services
 */
class Sms
{
    /**
     * @var string
     */
    public $smsLoginKey = '_sms_login';
    public $smsRegKey = '_sms_reg';
    /**
     * @var string
     */
    public $phone;

    /**
     * Sms constructor.
     * @param string $phone
     */
    public function __construct($phone)
    {
        $this->phone = $phone;
    }

    /**发送短信
     * @param $mobile
     * @param $content
     * @return bool
     */
    private function sendSms($smsCode = "", $cackeKey)
    {
        if (Cache::get($this->phone . $cackeKey)) {
            throw new CodeException(config('code.reg.code_30_valid'));
        }
        if (!Helper::mustSendSms()) {
            $this->setCacheCode("123456", $this->phone . $cackeKey);
            return true;
        }
        try {
            if (!$smsCode) {
                $smsCode = rand(1000, 9999);
            }
            $content = "您的验证码是：{$smsCode}，有效期30分钟.";
            $url = config('myconfig.sms_it_ip');
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $url, [
                'query' => [
                    'appCode' => 'BQQZD',
                    'appType' => '2',
                    'phone' => $this->phone,
                    'message' => $content
                ]
            ]);
            $res = json_decode($response->getBody()->getContents(), true);
            Logger::info(["message" => "手机号码{$this->phone}调用短信接口:{$url}", "return" => $res], 'sms');
            if (array_get($res, 'code') != '0') {
                throw new CodeException(config('code.reg.smscode_send_error'));
            }
            $this->setCacheCode($smsCode, $this->phone . $cackeKey);
        } catch (\Exception $e) {
            Logger::info($e->getMessage(), "sms");
            throw new CodeException(config('code.reg.smscode_send_error'));
        }
    }

    /**短信验证码判断
     * @param $code
     * @param $cackeKey
     * @return bool
     */
    private function checkSmsCode($code, $cackeKey)
    {
        $smsCache = Cache::get($this->phone . $cackeKey);
        return $code != "" && $smsCache == $code;
    }

    /**发送登录验证码
     * @param $smsCode
     */
    public function sendRegSms()
    {
        $this->sendSms("", $this->smsRegKey);
    }

    /**判断登录验证码
     * @param $code
     * @return bool
     */
    public function checkRegSms($code)
    {
        if (!$this->checkSmsCode($code, $this->smsRegKey)) {
            throw new CodeException(config('code.reg.smscode_error'));
        }
    }

    /**发送登录验证码
     * @param $smsCode
     */
    public function sendLoginSms()
    {
        $this->sendSms("", $this->smsLoginKey);
    }

    /**判断登录验证码
     * @param $code
     * @return bool
     */
    public function checkLoginSms($code)
    {
        if (!$this->checkSmsCode($code, $this->smsLoginKey)) {
            throw new CodeException(config('code.reg.smscode_error'));
        }
    }

    /**获取缓存验证码
     * @param $cackeKey
     * @return mixed
     */
    public function getCacheCode($cackeKey)
    {
        return Cache::get($cackeKey);
    }

    /**缓存验证码
     * @param $code
     * @param $cacheKey
     */
    public function setCacheCode($code, $cacheKey)
    {
        Cache::put($cacheKey, $code, 30);
    }

}