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

class Package
{
    public function packData($src, $des, $data)
    {
        $srcLen = dechex(strlen($src) / 2);
        $srcLen = str_pad($srcLen, 2, '0', STR_PAD_LEFT);
        $desLen = dechex(strlen($des) / 2);
        $desLen = str_pad($desLen, 2, '0', STR_PAD_LEFT);
        $totalLen = "";
        $totalLen = str_pad(dechex($totalLen), 4, '0', STR_PAD_LEFT);
        $str = "5252" . $totalLen . $srcLen . $src . $desLen . $des . $data . '00000D0A';
        return hex2bin(strtoupper($str));
    }

    public function unpackData($data)
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
            echo "len:" . $length . PHP_EOL;
            //to
            $toLen = hexdec(substr($data, 8, 2));
            //echo "toLen:" . $toLen . PHP_EOL;
            $toAddrSrc = substr($data, 10, $toLen * 2);
            echo "toAddr:" . $toAddrSrc . PHP_EOL;
            //from
            $fromLen = hexdec(substr($data, $toLen * 2 + 10, 2));
            //echo "fromLen :" . $fromLen . PHP_EOL;
            $fromAddrSrc = substr($data, $toLen * 2 + 12, $fromLen * 2);
            echo "fromAddr:" . $fromAddrSrc . PHP_EOL;
            $fromAddr = hex2bin($fromAddrSrc);
            //data
            $realdata = substr($data, $toLen * 2 + 12 + $fromLen * 2, -8);
            //echo "data:" . $realdata . PHP_EOL;

            $realdata=substr($realdata,4);
            echo $realdata;
            $realdata=Aes::opensslDecrypt($realdata,"");
            echo $realdata;
        } catch (\Exception $e) {
            Logger::info($data . "数据解析失败:" . $e->getTraceAsString(), 'mqtt');
            echo $e->getMessage() . PHP_EOL;
        }
    }
}