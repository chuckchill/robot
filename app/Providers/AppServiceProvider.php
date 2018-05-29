<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
<<<<<<< HEAD
        //
=======
        //admin-sidebar
        view()->composer('admin.layouts.sidebar','App\Http\ViewComposers\AdminMenuComposer');
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
<<<<<<< HEAD
        //
=======
        $this->app->register(RepositoryServiceProvider::class);
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    }
}
