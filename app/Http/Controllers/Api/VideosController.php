<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/13
 * Time: 10:09
 */

namespace App\Http\Controllers\Api;


use App\Models\Common\Videos;
use App\Services\Qiniu;
use Illuminate\Http\Request;

/**
 * Class VideosController
 * @package App\Http\Controllers\Api
 */
class VideosController extends BaseController
{
    /**
     * @return mixed
     */
    public function getVideosType()
    {
        $types = config("admin.videos.type");
        $data = [];
        foreach ($types as $key => $type) {
            $data[] = [
                "type_code" => $key,
                "type_name" => $type,
            ];
        }
        return $this->response->array([
            'code' => 0,
            'message' => '获取成功',
            'data' => (array)$data
        ]);
    }

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
        return $this->response->array([
            'code' => 0,
            'message' => '查询成功',
            'data' => [
                'videos' => $data['data'],
                'current_page' => $data['current_page']
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
        $user = \JWTAuth::authenticate();
        $returnBody = [
            "key" => "$(key)",
            "hash" => "$(hash)",
            "fsize" => "$(fsize)",
            "fname" => "$(fname)",
            "name" => "$(x:name)",
            "buid" => "{$user->id}",
        ];
        $policy = array(
            'callbackUrl' => route('qiniu.user-upload-callback'),
            'callbackBody' => json_encode($returnBody),
            'callbackBodyType' => 'application/json'
        );
        $token = $qn->getToken(config('qiniu.bucket.videos.bucket'), $policy);
        return $this->response->array([
            'code' => 0,
            'message' => '获取成功',
            'data' => [
                'token' => $token,
            ]
        ]);
    }

}