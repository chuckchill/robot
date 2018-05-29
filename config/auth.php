<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
<<<<<<< HEAD
    */

    'defaults' => [
        'guard' => 'web',
=======
     */

    'defaults' => [
        'guard' => 'users',
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
<<<<<<< HEAD
    */

    'guards' => [
        'web' => [
=======
     */

    'guards' => [
        'users' => [
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
            'driver' => 'session',
            'provider' => 'users',
        ],

<<<<<<< HEAD
=======
        'admin' => [
            'driver' => 'session',
            'provider' => 'admin',
        ],

>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
        'api' => [
            'driver' => 'token',
            'provider' => 'users',
        ],
<<<<<<< HEAD

        'admin'=>[
            'driver' => 'session',
            'provider' => 'admin_users',
        ],
=======
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
<<<<<<< HEAD
    */
=======
     */
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
<<<<<<< HEAD
        ],
        'admin_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin\AdminUser::class,
=======
            'table'=>'users',
        ],

        'admin' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
            'table'=>'admins'
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
<<<<<<< HEAD
=======
    | Here you may set the options for resetting passwords including the view
    | that is your password reset e-mail. You may also set the name of the
    | table that maintains all of the reset tokens for your application.
    |
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
<<<<<<< HEAD
    */
=======
     */
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a

    'passwords' => [
        'users' => [
            'provider' => 'users',
<<<<<<< HEAD
=======
            'email' => 'auth.emails.password',
            'table' => 'password_resets',
            'expire' => 60,
        ],
        'admin' => [
            'provider' => 'admin',
            'email' => 'auth.emails.password',
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

];
