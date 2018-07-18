<?php

namespace App\Service;

class Aes
{
    public function encrypt($input, $key = "")
    {
        $key = md5($key, true);
        echo $key . PHP_EOL;
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = $this->pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $data;
    }

    public function decrypt($sStr, $key = "")
    {
        $key = md5($key, true);
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $sStr, MCRYPT_MODE_ECB);
        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s - 1]);
        $decrypted = substr($decrypted, 0, -$padding);
        return $decrypted;
    }

    private function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    function strToHex($string)
    {
        $hex = "";
        for ($i = 0; $i < strlen($string); $i++)
            $hex .= dechex(ord($string[$i]));
        $hex = strtoupper($hex);
        return $hex;
    }

    function hexToStr($hex)
    {
        $string = "";
        for ($i = 0; $i < strlen($hex) - 1; $i += 2)
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        return $string;
    }


}