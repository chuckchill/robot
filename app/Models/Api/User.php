<?php

namespace App\Models\Api;

use Arcanedev\Support\Bases\Model;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Model implements JWTSubject, Authenticatable,Authorizable
{
    use \Illuminate\Auth\Authenticatable;

    protected $table='app_users';
    protected $dateFormat = "Y-m-d H:i:s";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
