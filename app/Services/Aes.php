<?php

namespace App\Services;

class Aes
{
    /**
     * [opensslDecrypt description]
     *
     * @param  [type] $sStr
     * @param  [type] $sKey
     * @return [type]
     */
    public static function opensslEncrypt($sStr, $sKey, $method = 'AES-128-ECB')
    {
        $sKey = md5($sKey, true);
        $str = openssl_encrypt($sStr, $method, $sKey);
        return bin2hex(base64_decode($str));
    }

    /**
     * [opensslDecrypt description]
     *
     * @param  [type] $sStr
     * @param  [type] $sKey
     * @return [type]
     */
    public static function opensslDecrypt($sStr, $sKey, $method = 'AES-128-ECB')
    {
        $sStr = base64_encode(hex2bin($sStr));
        $sKey = md5($sKey, true);
        $str = openssl_decrypt($sStr, $method, $sKey);
        return $str;
    }
}
