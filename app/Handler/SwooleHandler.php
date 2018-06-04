<?php

namespace App\Handler;
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
            echo "len:" . $length . PHP_EOL;
            //to
            $toLen = hexdec(substr($data, 8, 2));
            echo "toLen:" . $toLen . PHP_EOL;
            $toAddr = substr($data, 10, $toLen * 2);
            echo "toAddr:" . $toAddr . PHP_EOL;
            //from
            $fromLen = hexdec(substr($data, $toLen * 2 + 10, 2));
            echo "fromLen :" . $fromLen . PHP_EOL;
            $fromAddr = substr($data, $toLen * 2 + 12, $fromLen * 2);
            echo "fromAddr:" . $fromAddr . PHP_EOL;
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function onClose()
    {

    }

    public function onTask()
    {

    }
}
