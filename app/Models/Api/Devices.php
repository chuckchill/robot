<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/6/9
 * Time: 12:52
 */

namespace App\Models\Api;


use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Devices extends Model implements JWTSubject, Authenticatable,Authorizable
{
    use \Illuminate\Auth\Authenticatable;
    protected $table = 'devices';
    protected $dateFormat = "Y-m-d H:i:s";

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function can($ability, $arguments = [])
    {
        // TODO: Implement can() method.
    }
}