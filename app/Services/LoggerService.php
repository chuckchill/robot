<?php

namespace App\Services;

/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2017/9/22
 * Time: 14:13
 */


use Illuminate\Support\Facades\Log;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

/**
 * Class LoggerService
 * @package App\Services
 */
class LoggerService
{
    /**
     * @var
     */
    public $log;

    /**日志分割
     * @param $method
     * @return bool
     */
    public function useDailyFiles($method)
    {
        $dirpath = storage_path() . '/logs/' . date('Y-m-d', time());
        if (!file_exists($dirpath)) {
            $old_mask = umask(0);
            if (!mkdir($dirpath, 0777, true)) {
                Log::info("创建" . date('Y-m-d', time()) . "日志目录失败");
                return false;
            }
            umask($old_mask);
        }
        $this->log = new Logger('name');
        $handler = new RotatingFileHandler($dirpath . '/' . php_sapi_name() . '-' . $method . '.log');
        $this->log->setHandlers([$handler]);
    }

    /**记录日志
     * @param $message
     * @param string $type
     */
    public function info($message, $type = 'default')
    {
        $this->useDailyFiles($type . '-' . __FUNCTION__);
        if (is_array($message)) {
            $this->log->info('', $message);
        } else {
            $this->log->info($message);
        }

    }

}