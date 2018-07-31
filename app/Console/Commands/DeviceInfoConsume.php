<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/7/30
 * Time: 15:13
 */

namespace App\Console\Commands;


use App\Services\emq\EmqttClient;
use Illuminate\Console\Command;

class DeviceInfoConsume extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取设备信息';

    public function handle()
    {
        $mqtt = new EmqttClient("10.80.12.100", 1883, "123"); //Change client name to something unique

        if (!$mqtt->connect(true, NULL, "albert", "albert")) {
            exit(1);
        }
        $topics['publishtest'] = array("qos" => 0, "function" => [$this, "procmsg"]);
        $mqtt->subscribe($topics, 0);
        while ($mqtt->proc()) {

        }
        $mqtt->close();

    }

    public function procmsg($topic, $msg)
    {
        echo "Msg Recieved: " . date("r") . "\n";
        echo "Topic: {$topic}\n\n";
        echo "\t$msg\n\n";
    }
}