<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2015/11/3
 * Time: 16:11
 */

namespace App\Providers;


use App\Log\src\Logger;
use App\Log\src\UserLoggerRecord;
use App\Services\LoggerService;
use Illuminate\Support\ServiceProvider;

class LoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('logger',function(){
            return new LoggerService();
        });

    }
}