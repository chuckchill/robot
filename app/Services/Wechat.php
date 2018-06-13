<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/12
 * Time: 17:52
 */

namespace App\Services;


use App\Exceptions\CodeException;
use App\Facades\Logger;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

/**
 * Class Wechat
 * @package App\Services
 */
class Wechat
{
    /**
     * @var mixed
     */
    private $appId;
    /**
     * @var mixed
     */
    private $appSecret;

    /**
     * Wechat constructor.
     */
    public function __construct()
    {
        $this->appId = config("other.wx.app_id");
        $this->appSecret = config("other.wx.app_secret");
    }

    /**
     * @param $code
     * @return array|mixed
     * @throws CodeException
     */
    public function getToken($code)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token";
        $data = [
            'appid' => $this->appId,
            'secret' => $this->appSecret,
            'code' => $code,
            'grant_type' => 'authorization_code'
        ];
        $client = new Client();
        $response = $client->request('GET', $url, [
            'query' => $data,
            'verify' => false
        ]);
        $body = $response->getBody()->getContents();
        Logger::info("微信获取token返回:" . $body, "wx");
        $data = json_decode($body);
        if (array_get($data, "errcode")) {
            throw new CodeException(config("code.login.wechat_auth_error"));
        }
        return $data;
    }

    /**获取用户信息
     * @param $code
     * @return mixed
     * @throws CodeException
     */
    public function getUserInfo($code)
    {
        $token = $this->getToken($code);
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID";
        $reqData = [
            "access_token" => array_get($token, "access_token"),
            "openid" => array_get($token, "openid"),
        ];
        $client = new Client();
        $response = $client->request('GET', $url, [
            'query' => $reqData,
            'verify' => false
        ]);
        $body = $response->getBody()->getContents();
        Logger::info("微信获取个人信息返回:" . $body, "wx");
        $data = json_decode($body);
        if (array_get($data, "errcode") || !array_get($data, "unionid")) {
            throw new CodeException(config("code.login.wechat_auth_error"));
        }
        return $data;
    }

    public function storeImage($path)
    {
        if (!$path) {
            return "";
        }
        $data = file_get_contents($path);
        $savePath = "upload/wx/prifile_img_" . time() . ".jpg";
        file_put_contents(public_path($savePath), $data);
        return $savePath;
    }
}