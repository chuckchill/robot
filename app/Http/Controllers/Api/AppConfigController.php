<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/6/6
 * Time: 22:40
 */

namespace App\Http\Controllers\Api;


use App\Models\Common\LinkPage;
use App\Models\Common\StartupPage;
use App\Services\ModelService\AreaCode;
use App\Services\ModelService\MediaType;

/**
 * Class AppConfigController
 * @package App\Http\Controllers\Api
 */
class AppConfigController extends BaseController
{
    /**
     * @return mixed
     */
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

    /**
     * @return mixed
     */
    public function linkPage()
    {
        $link = LinkPage::where(['status' => 1])
            ->orderBy('id', SORT_DESC)
            ->first();
        $url = $link ? $link->imgsrc : "";
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

    /**
     * @return mixed
     */
    public function getCityCode()
    {
        return $this->response
            ->array([
                'code' => 0,
                'message' => "获取成功",
                "data" => [
                    'area_code' => AreaCode::getCityTree()
                ]
            ]);
    }

    /**
     * @return mixed
     */
    public function getMediaType()
    {
        $data = MediaType::getTypeTree(false);
        return $this->response->array([
            'code' => 0,
            'message' => '获取成功',
            'data' => (array)$data
        ]);
    }
}