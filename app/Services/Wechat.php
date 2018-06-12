<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/12
 * Time: 17:52
 */

namespace App\Services;


use GuzzleHttp\Client;

class Wechat
{
    private $appId;
    private $appSecret;

    public function __construct()
    {
        $this->appId = config("other.wx.app_id");
        $this->appSecret = config("other.wx.app_secret");
    }

    public function getToken($code)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token";
        $data = [
            'appid' => $this->appId,
            'secret' => $this->appSecret,
            'code' => $code,
            'grant_type' => 'authorization_code'
        ];
        $client= new Client();
        $response = $client->request('GET', $url, [
            'query' => $data
        ]);
        return $response;
    }
}