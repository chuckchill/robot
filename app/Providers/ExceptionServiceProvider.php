<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/13
 * Time: 13:57
 */

namespace App\Providers;


use App\Exceptions\ApiHandler;
use App\Exceptions\CodeException;
use App\Exceptions\CodeExceptionHandler;
use Dingo\Api\Provider\ServiceProvider;

class ExceptionServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app['api.exception']->register(function (CodeException $exception) {
            return response()->json([
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);
        });
        /*$this->app['config'];
        $this->app->singleton('api.exception', function ($app) {
            return new ApiHandler($app['Illuminate\Contracts\Debug\ExceptionHandler'], $this->config('errorFormat'), $this->config('debug'));
        });*/
    }
}