<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/9/18
 * Time: 11:00
 */

namespace App\Http\Controllers\Api\Traits;

use App\Services\Validator;
use Illuminate\Http\Request;

trait SikerTrait
{

    public function addSicker(Request $request)
    {dd($request->get('device'));
        $province = $request->get('province');
        $city = $request->get('city');
        $country = $request->get('country');
        $sicker_name = $request->get('sicker_name');
        $sicker_idcard = $request->get('sicker_idcard');
        $doctor_name = $request->get('doctor_name');
        $doctor_no = $request->get('doctor_no');
        $type = $request->get('type');
        if (!$province || !$city || !$country || !$sicker_name ||
            !$doctor_name || !$doctor_no || !$type || !$sicker_idcard) {
            code_exception('code.common.not_null');
        }
    }

    public function editSicker(Request $request)
    {
        $province = $request->get('province');
        $city = $request->get('city');
        $country = $request->get('country');
        $sicker_name = $request->get('sicker_name');
        $sicker_idcard = $request->get('sicker_idcard');
        $doctor_name = $request->get('doctor_name');
        $doctor_no = $request->get('doctor_no');
        $type = $request->get('type');
        if (!$province || !$city || !$country || !$sicker_name ||
            !$doctor_name || !$doctor_no || !$type || !$sicker_idcard) {
            code_exception('code.common.not_null');
        }
    }

    public function delSicker(Request $request)
    {

    }
}