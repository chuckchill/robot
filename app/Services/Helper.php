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
use \Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;

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
    public function codeException($postfix, $content = '')
    {
        $codes = config($postfix);
        if ($content) {
            $codes['message'] = $content;
        }
        throw new CodeException($codes);
    }

    /**
     * @param $key
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public static function getVideoThumb($key)
    {
        return url("/images/video.jpg");
        try {
            $saveKey = md5($key);
            $thumbPath = 'upload/video_thumb';
            $thumbPath = self::mkDir($thumbPath);
            $dirPath = public_path($thumbPath . "/" . $saveKey . ".jpg");
            if (!file_exists($dirPath)) {
                $qn = new Qiniu();
                $url = $qn->getVideoThumb($key);
                if (!self::checkThumb($url)) {
                    return "";
                }
                $content = file_get_contents($url);
                file_put_contents($dirPath, $content);

            }
            return url($thumbPath . "/" . $saveKey . ".jpg");
        } catch (Exception $exception) {
            Logger::info("key：{$key}不存在", "qiniu");
            return "";
        }
    }

    /**
     * @param $url
     * @return bool
     */
    public static function checkThumb($url)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        return $response->getStatusCode() != "200";
    }

    /**
     * @param string $ipAddr
     * @return array
     */
    public static function getIpArea($ipAddr = '')
    {
        if (!$ipAddr) {
            $ipAddr = Request::getClientIp();
        }
        $client = new \GuzzleHttp\Client();
        $url = "http://ip.taobao.com/service/getIpInfo.php";
        $response = $client->request('GET', $url, [
            'query' => [
                'ip' => $ipAddr
            ]
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
        if (array_get($result, 'data.city_id') == 'local') {
            return [
                'province' => '',
                'city' => '',
            ];
        }
        return [
            'province' => array_get($result, 'data.region_id'),
            'city' => array_get($result, 'data.city_id')
        ];
    }

    /**
     * @param null $user
     * @return array
     */
    public static function getUserArea($user = null)
    {
        if ($user && ($user->province || $user->city)) {
            return [
                'province' => $user->province,
                'city' => $user->city
            ];
        }
        return self::getIpArea();
    }

    /**
     * @param $idCard
     * @return bool
     */
    public static function validIdcard($idCard)
    {
        $id = strtoupper($idCard);
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
        $arr_split = array();
        if (!preg_match($regx, $id)) {
            return false;
        }
        if (15 == strlen($id)) {
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
            @preg_match($regx, $id, $arr_split);
            //检查生日日期是否正确
            $dtm_birth = "19" . $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
            if (!strtotime($dtm_birth)) {
                return false;
            } else {
                return true;
            }
        } else {
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
            @preg_match($regx, $id, $arr_split);
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
            if (!strtotime($dtm_birth)) {
                return false;
            } else {
                $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $sign = 0;
                for ($i = 0; $i < 17; $i++) {
                    $b = (int)$id{$i};
                    $w = $arr_int[$i];
                    $sign += $b * $w;
                }
                $n = $sign % 11;
                $val_num = $arr_ch[$n];
                if ($val_num != substr($id, 17, 1)) {
                    return false;
                } else {
                    return true;
                }
            }
        }
    }

    public static function Participle($keyword)
    {
        $client = new Client();
        $url = "http://zhannei.baidu.com/api/customsearch/keywords";
        $response = $client->request('GET', $url, [
            'query' => [
                'title' => $keyword,
            ]
        ]);
        $res = json_decode($response->getBody()->getContents(), true);
        $keywords = array_get($res, 'result.res.keyword_list');
        if (!$keywords) {
            return $keyword ? [$keyword] : [];
        }
        return $keywords;
    }
}