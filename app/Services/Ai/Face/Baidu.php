<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/12/4
 * Time: 14:59
 */

namespace App\Services\Ai\Face;


use App\Facades\Logger;

/**
 * Class Baidu
 * @package App\Services\Ai\Face
 */
class Baidu
{
    /**
     * @var mixed
     */
    private $apiKey;
    /**
     * @var mixed
     */
    private $secretKey;
    /**
     * @var mixed
     */
    private $thresHold;

    /**
     * Baidu constructor.
     */
    public function __construct()
    {
        $this->apiKey = config('ai.face.baidu.apiKey');
        $this->secretKey = config('ai.face.baidu.secretKey');
        $this->thresHold = config('ai.face.baidu.thresHold');
    }

    /**获取token
     * @return mixed
     */
    public function getAccessToken()
    {
        $url = "https://aip.baidubce.com/oauth/2.0/token";
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('POST', $url, [
                'verify' => false,
                'query' => [
                    'client_id' => $this->apiKey,
                    'client_secret' => $this->secretKey,
                    'grant_type' => 'client_credentials',
                ]
            ]);
            $result = json_decode($response->getBody(), true);
            return array_get($result, 'access_token');
        } catch (\Exception $exception) {
            Logger::info($exception->getMessage(), 'face_err');
            code_exception('code.common.server_error');
        }
    }

    public function faceMatch(&$srcImg, &$locolImg)
    {
        $url = "https://aip.baidubce.com/rest/2.0/face/v3/match";
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('POST', $url, [
                'verify' => false,
                'query' => [
                    'access_token' => $this->getAccessToken(),
                ],
                'json' => [
                    [
                        'image' => $srcImg,
                        'image_type' => 'BASE64',
                        'face_type' => 'LIVE',
                        'quality_control' => 'LOW',
                        'liveness_control' => 'HIGH',
                    ],
                    [
                        'image' => $locolImg,
                        'image_type' => 'BASE64',
                        'face_type' => 'LIVE',
                        'quality_control' => 'LOW',
                        'liveness_control' => 'HIGH',
                    ],
                ],
                'headers' => [
                    'Content-type' => 'application/json',
                    "Accept" => "application/json"
                ]
            ]);
            Logger::info($response->getBody(), 'face');
            $result = json_decode($response->getBody(), true);
            if (array_get($result, 'error_code') != 0 &&
                array_get($result, 'result.score') < $this->thresHold) {
                return false;
            }
            return true;
        } catch (\Exception $exception) {
            Logger::info($exception->getMessage(), 'face_err');
            code_exception('code.common.server_error');
        }
    }
}