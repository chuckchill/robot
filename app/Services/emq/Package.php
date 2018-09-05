<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/9/3
 * Time: 21:14
 */

namespace App\Services\emq;


use App\Facades\Logger;
use App\Services\Aes;
use Illuminate\Support\Facades\Redis;
use function Psy\bin;

/**
 * Class Package
 * @package App\Services\emq
 */
class Package
{
    /**
     * @var string
     */
    public $inKey = "123";

    /**
     * @param $des
     * @param $content
     * @param $cmd
     * @return string
     * @throws \Exception
     */
    public function encodeData($des, $content, $cmd)
    {
        $key = $this->generateKey();//随机key
        $pn = $this->getPackageNo($des);//包序列号
        $cmd = Cmd::getCmdCode($cmd);//指令
        $secretKey = hex2bin($key) . $this->inKey;
        return $key . Aes::opensslEncrypt(gzencode(hex2bin($pn . $cmd . $content)), $secretKey);
    }

    /**
     * @param $data
     * @return array
     */
    public function decodeData($data)
    {
        $key = substr($data, 0, 8);
        $data = substr($data, 8);
        $secretKey = hex2bin($key) . $this->inKey;
        $decode = Aes::opensslDecrypt($data, $secretKey);
        $decode = bin2hex(gzdecode($decode));
        return [
            'sno' => hexdec(substr($decode, 0, 4)),//序列号
            'data' => substr($decode, 8),
            'directive' => substr($decode, 4, 4)//
        ];
    }

    /**打包协议数据
     * @param $src
     * @param $des
     * @param $content string 内容
     * @param $cmd  string 指令
     * @return bool|string
     * @throws \Exception
     */
    public function packProtocol($src, $des, $content, $cmd)
    {
        $src = bin2hex($src);
        $des = bin2hex($des);
        $content = bin2hex($content);
        $srcLen = dechex(strlen($src) / 2);
        $srcLen = str_pad($srcLen, 2, '0', STR_PAD_LEFT);
        $desLen = dechex(strlen($des) / 2);
        $desLen = str_pad($desLen, 2, '0', STR_PAD_LEFT);
        $data = $this->encodeData($des, $content, $cmd);
        $str = "5252" . $srcLen . $src . $desLen . $des . $data . '00000D0A';
        $totalLen = strlen($str) + 4;
        $totalLen = str_pad(dechex($totalLen / 2), 4, '0', STR_PAD_LEFT);
        $str = "5252" . $totalLen . $srcLen . $src . $desLen . $des . $data . '00000D0A';
        return hex2bin(strtoupper($str));
    }


    /**解析协议数据
     * @param $data
     */
    public function unpackProtocol($data)
    {
        $data = bin2hex($data);
        try {
            $start = substr($data, 0, 4);
            if ($start !== '5252') {
                throw new \Exception("数据格式错误:" . $data);
            }
            $totalLen = strlen($data);
            $length = hexdec(substr($data, 4, 4));
            if ($totalLen != $length * 2) {
                throw new \Exception("数据解码错误:" . $data);
            }
            $toLen = hexdec(substr($data, 8, 2));
            $toAddrSrc = substr($data, 10, $toLen * 2);
            dump($toAddrSrc);
            $fromLen = hexdec(substr($data, $toLen * 2 + 10, 2));
            $fromAddrSrc = substr($data, $toLen * 2 + 12, $fromLen * 2);
            dump($fromAddrSrc);
            $fromAddr = hex2bin($fromAddrSrc);
            $realdata = substr($data, $toLen * 2 + 12 + $fromLen * 2, -8);
            $decode = $this->decodeData($realdata);
            return $decode;
        } catch (\Exception $e) {
            Logger::info($data . "数据解析失败:" . $e->getTraceAsString(), 'mqtt');
            echo $e->getMessage() . PHP_EOL;
        }
    }

    /**生产key
     * @return string
     */
    public function generateKey()
    {
        return "08060707";
        return bin2hex(str_random(4));
    }

    /**
     * @param $des
     * @return string
     */
    public function getPackageNo($des)
    {
        return '0001';
        $key = 'pn_' . md5($des);
        $pn = Redis::get($key);
        if (!$pn || $pn > 1000000) {
            $pn = 0;
            Redis::set($key, $pn);
        }
        Redis::incr($key);
        return str_pad(dechex($pn), 4, '0', STR_PAD_LEFT);
    }
}