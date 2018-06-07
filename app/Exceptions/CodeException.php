<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/7
 * Time: 18:08
 */

namespace App\Exceptions;


use Throwable;

class CodeException extends \Exception
{
    public function __construct($source = [], Throwable $previous = null)
    {
        parent::__construct(array_get($source, 'message'), array_get($source, 'code'), $previous);
    }
}