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

/**
 * Class Qiniu
 * @package App\Services
 */
class Qiniu
{
    /**
     * @var Auth
     */
    public $Auth;

    /**
     * Qiniu constructor.
     */
    public function __construct()
    {
        $accessKey = config('qiniu.accessKey');
        $secretKey = config('qiniu.secretKey');
        $this->Auth = new Auth($accessKey, $secretKey);
    }

    /**获取token
     * @param $bucket
     * @param array $policy
     * @return string
     */
    public function getToken($bucket, $policy = [])
    {
        return $this->Auth->uploadToken($bucket, null, 3600, $policy, true);
    }

    /**视频播放地址
     * @param $key
     * @param int $expires
     * @return string
     */
    public function getDownloadUrl($key, $expires = 3600)
    {
        $baseUrl = config('qiniu.bucket.videos.private_url') . '/' . $key;
        $signedUrl = $this->Auth->privateDownloadUrl($baseUrl, $expires);
        return $signedUrl;
    }

    /**视频展示图
     * @param $key
     * @param int $expires
     * @return string
     */
    public function getVideoThumb($key, $expires = 3600)
    {
        $baseUrl = config('qiniu.bucket.videos.private_url') . '/' . $key."?vframe/jpg/offset/5";
        $signedUrl = $this->Auth->privateDownloadUrl($baseUrl, $expires);
        return $signedUrl;
    }

    /**回调验证
     * @param $url
     * @return bool
     */
    public function vertifyCallback($url)
    {
        $contentType = array_get(Request::header(), "content-type.0");
        $callbackBody = file_get_contents('php://input');
        $authorization = Request::server("HTTP_AUTHORIZATION");
        $signRequest = $this->Auth->signRequest($url, $callbackBody, $contentType);
        Logger::info("鉴权参数:" . json_encode(compact("callbackBody", "contentType", "authorization", "url", "signRequest")), 'qiniu');
        return $this->Auth->verifyCallback($contentType, $authorization, $url, $callbackBody);
    }

    /**
     * @return BucketManager
     */
    public function getBucketManager()
    {
        $config = new Config();
        return new BucketManager($this->Auth, $config);
    }

}