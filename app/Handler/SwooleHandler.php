<?php

namespace App\Handler;

use Illuminate\Support\Facades\Redis;

/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/6/4
 * Time: 19:50
 */
class SwooleHandler
{
    public function onStart(\swoole_server $server)
    {
        echo "start at:" . $server->host . ":" . $server->port . PHP_EOL;
    }

    public function onConnect(\swoole_server $server, $fd, $reactorId)
    {
        echo "Client: Connect{$fd}.\n";
    }

    public function onReceive(\swoole_server $serv, $fd, $from_id, $data)
    {
        $data=bin2hex($data);
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
            //echo "len:" . $length . PHP_EOL;
            //to
            $toLen = hexdec(substr($data, 8, 2));
            //echo "toLen:" . $toLen . PHP_EOL;
            $toAddrSrc = substr($data, 10, $toLen * 2);
            //echo "toAddr:" . $toAddrSrc . PHP_EOL;
            //from
            $fromLen = hexdec(substr($data, $toLen * 2 + 10, 2));
            //echo "fromLen :" . $fromLen . PHP_EOL;
            $fromAddrSrc = substr($data, $toLen * 2 + 12, $fromLen * 2);
            //echo "fromAddr:" . $fromAddrSrc . PHP_EOL;
            $fromAddr = hex2bin($fromAddrSrc);
            //data
            $realdata = substr($data, $toLen * 2 + 12 + $fromLen * 2, -8);
            //echo "data:" . $realdata . PHP_EOL;
            if ($toAddrSrc == "00000000") {
                if (strpos($fromAddr, '_') == false) {
                    Redis::set("robot:info:{$fromAddr}", $data);//s上传机器人设备信息
                }
                $serv->send($fd, $this->packData($fromAddrSrc, $toAddrSrc, $realdata, $length));
            } else if ($toAddrSrc == "FFFFFFFF") {
                foreach ($serv->connections as $fd) {
                    $serv->send($fd, $data);//广播数据
                }
                return true;
            } else {
                $toAddr = hex2bin($toAddrSrc);
                $md5Key = md5($toAddr);
                $toFd = Redis::get("addr:{$md5Key}");
                if ($toFd != null) {
                    //转发数据
                    $serv->send($toFd, $data);
                }
            }
            Redis::set("addr:{$fd}", $fromAddr);
            $md5Key = md5($fromAddr);
            Redis::set("fd:{$md5Key}", $fd);
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function onClose(\swoole_server $serv, $fd, $reactorId)
    {
        $this->clearStorage($fd);
    }

    public function onShutdown(\swoole_server $serv)
    {
        foreach ($serv->connections as $key => $fd) {
            $this->clearStorage($fd);
        }
    }

    public function onFinish()
    {
        echo "finish";
    }

    public function onTask()
    {

    }

    public function clearStorage($fd)
    {
        $addr = md5(Redis::get("addr:{$fd}"));
        Redis::del("addr:{$fd}");
        Redis::del("fd:{$addr}");
        echo "close {$fd}" . PHP_EOL;

    }

    public function packData($src, $des, $data, $totalLen)
    {
        $srcLen = dechex(strlen($src) / 2);
        $srcLen = str_pad($srcLen, 2, '0', STR_PAD_LEFT);
        $desLen = dechex(strlen($des) / 2);
        $desLen = str_pad($desLen, 2, '0', STR_PAD_LEFT);
        $totalLen = str_pad(dechex($totalLen), 4, '0', STR_PAD_LEFT);
        $str = "5252" . $totalLen . $srcLen . $src . $desLen . $des . $data . '00000D0A';
        return strtoupper(hex2bin($str));
    }
}
