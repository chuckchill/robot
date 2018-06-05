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
            echo "toLen:" . $toLen . PHP_EOL;
            $toAddr = hex2bin(substr($data, 10, $toLen * 2));
            echo "toAddr:" . $toAddr . PHP_EOL;
            //from
            $fromLen = hexdec(substr($data, $toLen * 2 + 10, 2));
            echo "fromLen :" . $fromLen . PHP_EOL;
            $fromAddr = hex2bin(substr($data, $toLen * 2 + 12, $fromLen * 2));
            echo "fromAddr:" . $fromAddr . PHP_EOL;
            //data
            $data = substr($data, $toLen * 2 + 12 + $fromLen * 2, -8);
            echo "data:" . $data . PHP_EOL;
            if ($toAddr == "00000000" && strpos($fromAddr, '_') == false) {
                Redis::set("robot:info:{$fromAddr}", $data);//s上传机器人设备信息
            } else if ($toAddr == "FFFFFFFF") {
                foreach ($serv->connections as $fd) {
                    $serv->send($fd, $data);//广播数据
                }
            } else {
                $serialNo = Redis::get("addr:{$fd}");
                $md5Key = md5($serialNo);
                $toFd = Redis::get("addr:{$md5Key}");
                if ($toFd != null) {
                    //转发数据
                    $serv->send($toFd, $data);
                }
            }
            Redis::set("addr:{$fd}", $fromAddr);
            $md5Key = md5($fromAddr);
            Redis::set("fd:{$md5Key}", $fd);
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function onClose(\swoole_server $serv, $fd, $reactorId)
    {
        $addr = md5(Redis::get("addr:{$fd}"));
        Redis::del("addr:{$fd}");
        Redis::del("fd:{$addr}");
        echo "{$fd}关闭";
    }

    public function onFinish()
    {

    }

    public function onTask()
    {

    }
}
