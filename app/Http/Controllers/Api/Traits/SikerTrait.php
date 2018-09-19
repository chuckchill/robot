<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/9/18
 * Time: 11:00
 */

namespace App\Http\Controllers\Api\Traits;

use App\Models\Common\Sicker;
use App\Services\Helper;
use App\Services\Validator;
use Illuminate\Http\Request;

/**
 * Trait SikerTrait
 * @package App\Http\Controllers\Api\Traits
 */
trait SikerTrait
{
    public function getSicker(Request $request)
    {
        $device = $request->get('device');
        $sicker = Sicker::where(['device_id' => $device->id])->get();
        return $this->response->array([
            'code' => 0,
            'message' => '获取成功',
            'data' => $sicker->toArray()
        ]);
    }

    /**添加病人
     * @param Request $request
     */
    public function addSicker(Request $request)
    {
        $sicker = new Sicker();
        $this->saveSicker($sicker, $request);
        return $this->response->array([
            'code' => 0,
            'message' => '添加成功',
        ]);
    }

    /**修改病人
     * @param Request $request
     */
    public function editSicker(Request $request)
    {
        $sicker = Sicker::find($request->get('sicker_id'));
        if (!$sicker) {
            code_exception('code.common.item_notexist');
        }
        $this->saveSicker($sicker, $request);
        return $this->response->array([
            'code' => 0,
            'message' => '修改成功',
        ]);
    }

    /**删除病人
     * @param Request $request
     * @return mixed
     */
    public function delSicker(Request $request)
    {
        $device = $request->get('device');
        $sicker = Sicker::where(['id' => $request->get('sicker_id'), 'device_id' => $device->id])->first();
        if ($sicker) {
            $sicker->status = 2;
            $sicker->save();
        }
        return $this->response->array([
            'code' => 0,
            'message' => '删除成功',
        ]);
    }

    /**保存信息
     * @param Sicker $sicker
     * @param Request $request
     */
    protected function saveSicker(Sicker $sicker, Request $request)
    {
        $province = $request->get('province');
        $city = $request->get('city');
        $country = $request->get('country');
        $sicker_name = $request->get('sicker_name');
        $sicker_idcard = $request->get('sicker_idcard');
        $doctor_name = $request->get('doctor_name');
        $doctor_no = $request->get('doctor_no');
        $type = $request->get('type');
        $device = $request->get('device');
        if (!Helper::validIdcard($sicker_idcard)) {
            code_exception('code.common.idcard_invalid');
        }
        if (!$province || !$city || !$country || !$sicker_name ||
            !$doctor_name || !$doctor_no || !$type) {
            code_exception('code.common.not_null');
        }
        $sicker->province = $province;
        $sicker->device_id = $device->id;
        $sicker->city = $city;
        $sicker->country = $country;
        $sicker->sicker_name = $sicker_name;
        $sicker->sicker_idcard = $sicker_idcard;
        $sicker->doctor_name = $doctor_name;
        $sicker->doctor_no = $doctor_no;
        $sicker->type = $type;
        $sicker->save();
    }
}