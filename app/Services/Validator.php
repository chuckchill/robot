<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2017/9/25
 * Time: 17:43
 */

namespace App\Services;


class Validator
{
    public static function check($data, $validators)
    {
        $validator = \Validator::make($data, $validators);
        if ($validator->fails()) {
            return bad_request($validator->errors()->first());
        }
    }
}