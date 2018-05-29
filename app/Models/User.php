<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

=======
use Illuminate\Foundation\Auth\User as AuthUser;

class User extends AuthUser
{
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
<<<<<<< HEAD
     * The attributes that should be hidden for arrays.
=======
     * The attributes excluded from the model's JSON form.
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
<<<<<<< HEAD
=======

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
}
