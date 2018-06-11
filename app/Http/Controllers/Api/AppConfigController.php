<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/6/6
 * Time: 22:40
 */

namespace App\Http\Controllers\Api;


use App\Models\Common\BootPage;
use App\Models\Common\StartupPage;
use DeepCopy\f006\B;
use function PHPSTORM_META\map;

class AppConfigController extends BaseController
{
    public function startupPpage()
    {
        $startup = StartupPage::where(['status' => 1])
            ->orderBy('id', SORT_DESC)
            ->first();
        $url = $startup ? $startup->imgsrc : "";
        return $this->response
            ->array([
                'code' => 0,
                'message' => "获取成功",
                "data" => [
                    'url' => startup_img($url)
                ]
            ]);
    }

    public function linkPage()
    {
        $boot = BootPage::where(['status' => 1])
            ->orderBy('id', SORT_DESC)
            ->first();
        $url = $boot ? $boot->imgsrc : "";
        $urls = explode("@", $url);
        $urls = array_map(function ($url) {
            return link_img($url);
        }, $urls);
        return $this->response
            ->array([
                'code' => 0,
                'message' => "获取成功",
                "data" => [
                    'url' => array_filter($urls)
                ]
            ]);
    }
}