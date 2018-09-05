<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/9/5
 * Time: 9:17
 */

namespace App\Services\emq;


/**
 * Class Cmd
 * @package App\Services\emq
 */
class Cmd
{
    /**
     * @param $type
     * @return string
     * @throws \Exception
     */
    public static function getCmdCode($type)
    {
        switch ($type) {
            case 'setAc'://日常提醒
                return '1001';
                break;
            case 'logOut'://退出登录
                return '1002';
                break;
            default:
                throw new \Exception("未知命令");
        }
    }

    /**处理客户端信息
     * @param $code
     * @param $data
     * @throws \Exception
     */
    public function handleCmd($code, $data)
    {
        switch ($code) {
            case '2001'://设备信息
                $this->handleDeviceInfo($data);
                break;
            default:
                throw new \Exception("未知指令");
        }
    }

    /**保存设备信息
     * @param $data
     */
    public function handleDeviceInfo($data)
    {

    }

}