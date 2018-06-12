<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/5/31
 * Time: 11:27
 */

namespace App\Services;


use App\Facades\Logger;
use Illuminate\Support\Facades\Request;
use Qiniu\Auth;
use Qiniu\Config;
use Qiniu\Storage\BucketManager;

class Qiniu
{
    public $Auth;

    public function __construct()
    {
        $accessKey = config('qiniu.accessKey');
        $secretKey = config('qiniu.secretKey');
        $this->Auth = new Auth($accessKey, $secretKey);
    }

    public function getToken($bucket, $policy = [])
    {
        return $this->Auth->uploadToken($bucket, null, 3600, $policy, true);
    }

    public function getDownloadUrl($key, $expires = 3600)
    {
        $baseUrl = config('qiniu.bucket.videos.private_url') . '/' . $key;
        $signedUrl = $this->Auth->privateDownloadUrl($baseUrl, $expires);
        return $signedUrl;
    }

    public function vertifyCallback($url)
    {
        $contentType = array_get(Request::header(), "content-type.0");
        $callbackBody = file_get_contents('php://input');
        $authorization = Request::server("HTTP_AUTHORIZATION");
        $signRequest = $this->Auth->signRequest($url, $callbackBody, $contentType);
        Logger::info("鉴权参数:" . json_encode(compact("callbackBody", "contentType", "authorization", "url", "signRequest")), 'qiniu');
        return $this->Auth->verifyCallback($contentType, $authorization, $url, $callbackBody);
    }

    public function getBucketManager()
    {
        $config = new Config();
        return new BucketManager($this->Auth, $config);
    }

}