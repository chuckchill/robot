<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/7
 * Time: 15:49
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\CodeException;
use App\Http\Controllers\Controller;
use Dingo\Api\Exception\ValidationHttpException;
use Dingo\Api\Routing\Helpers;

/**
 * Class BaseController
 * @package App\Http\Controllers\Api
 */
class BaseController extends Controller
{
    use Helpers;

    /**错误
     * @param $validator
     */
    protected function errorBadRequest($validator)
    {
        // github like error messages
        // if you don't like this you can use code bellow
        //
        //throw new ValidationHttpException($validator->errors());

        $result = [];
        $messages = $validator->errors()->toArray();

        if ($messages) {
            foreach ($messages as $field => $errors) {
                foreach ($errors as $error) {
                    $result[] = [
                        'field' => $field,
                        'code' => $error,
                    ];
                }
            }
        }

        throw new ValidationHttpException($result);
    }

    /**
     * @param $validator
     */
    protected function firstError($validator)
    {
        $this->response->errorBadRequest($validator->errors()->first());
    }

    /**
     * @param $codes
     * @throws CodeException
     */
    protected function codeException($codes)
    {
        throw new CodeException(config($codes));
    }
}