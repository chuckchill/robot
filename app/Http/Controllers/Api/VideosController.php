<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/13
 * Time: 10:09
 */

namespace App\Http\Controllers\Api;


use App\Models\Api\DeviceBind;
use App\Models\Api\User;
use App\Models\Common\LiveVideos;
use App\Models\Common\Videos;
use App\Services\Helper;
use App\Services\ModelService\MediaType;
use App\Services\Qiniu;
use Illuminate\Http\Request;

/**
 * Class VideosController
 * @package App\Http\Controllers\Api
 */
class VideosController extends BaseController
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function getVideos(Request $request)
    {
        $typeCode = $request->get('type_code');
        $searchName = $request->get('search');
        $query = Videos::select("name", "key", "created_at");
        if ($typeCode) {
            $query->where(['type' => $typeCode]);
        }
        if ($searchName) {
            $query->where('name', 'like', $searchName . '%');
        }
        $data = $query->paginate(15)->toArray();
        $curPage = $data['current_page'];
        $items = $data['data'];
        foreach ($items as $key => $item) {
            $items[$key]['thumb'] = Helper::getVideoThumb($item['key']);
        }
        return $this->response->array([
            'code' => 0,
            'message' => '查询成功',
            'data' => [
                'videos' => $items,
                'current_page' => $curPage
            ]
        ]);
    }

    /**直播
     * @param Request $request
     * @return mixed
     */
    public function getLiveVideos(Request $request)
    {
        $user = \JWTAuth::authenticate();
        $province = $request->get('province');
        $city = $request->get('city');
        if (!$province && !$city) {
            $userArea = Helper::getUserArea();
            $province = array_get($userArea, 'province');
            $city = array_get($userArea, 'city');
        }

        $query = LiveVideos::select("name", "key", "created_at")/*->where(["uid" => $user->id])*/
        ;
        if ($province) {
            $query->where(['province' => $province]);
        }
        if ($city) {
            $query->where(['city' => $city]);
        }
        $data = $query->paginate(15)->toArray();
        $curPage = $data['current_page'];
        $items = $data['data'];
        foreach ($items as $key => $item) {
            $items[$key]['thumb'] = Helper::getVideoThumb($item['key']);
        }
        return $this->response->array([
            'code' => 0,
            'message' => '查询成功',
            'data' => [
                'videos' => $items,
                'current_page' => $curPage
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getVideoSrc(Request $request)
    {
        $key = $request->get("key");
        $qn = new Qiniu();
        $src = $qn->getDownloadUrl($key);
        return $this->response->array([
            'code' => 0,
            'message' => '获取成功',
            'data' => [
                'src' => $src,
            ]
        ]);
    }

    /**
     * @return mixed
     */
    public function getUploadToken()
    {
        $qn = new Qiniu();
        $device = \JWTAuth::authenticate();
        $deviceBind = DeviceBind::where(['device_id' => $device->id, 'is_master' => 1])->first();
        $user = User::find($deviceBind->uid)->first();
        $areaCode = Helper::getUserArea($user);
        $returnBody = [
            "key" => "$(key)",
            "hash" => "$(hash)",
            "fsize" => "$(fsize)",
            "fname" => "$(fname)",
            "name" => "$(x:name)",
            "type" => "$(x:type)",
            "uid" => "{$user->id}",
            "province" => array_get($areaCode, "province"),
            "city" => array_get($areaCode, "city"),
        ];
        $policy = array(
            'callbackUrl' => route('qiniu.user-upload-callback'),
            'callbackBody' => json_encode($returnBody),
            'callbackBodyType' => 'application/json'
        );
        $token = $qn->getToken(config('qiniu.bucket.videos.bucket'), $policy, 3600 * 24);
        return $this->response->array([
            'code' => 0,
            'message' => '获取成功',
            'data' => [
                'token' => $token,
            ]
        ]);
    }

}