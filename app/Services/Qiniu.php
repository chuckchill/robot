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

    public function getDownloadUrl()
    {
        $baseUrl = 'http://if-pri.qiniudn.com/qiniu.png?imageView2/1/h/500';
        $signedUrl = $this->Auth->privateDownloadUrl($baseUrl);
        echo $signedUrl;
    }

    public function vertifyCallback()
    {
        $contentType = 'application/x-www-form-urlencoded';
        $callbackBody = file_get_contents('php://input');
        $authorization = Request::server("HTTP_AUTHORIZATION");
        $url = route('qiniu.callback');
        Logger::info("鉴权参数:".json_encode(compact("callbackBody","contentType","authorization","url")),'qiniu');
        return $this->Auth->verifyCallback($contentType, $authorization, $url, $callbackBody);
    }
}