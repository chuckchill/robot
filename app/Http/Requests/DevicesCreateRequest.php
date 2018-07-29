<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/7/28
 * Time: 17:52
 */

namespace App\Http\Requests;


class DevicesCreateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'sno'=>'required|unique:devices|max:255',
        ];
    }
}