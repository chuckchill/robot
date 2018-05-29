<?php

namespace App\Providers;

<<<<<<< HEAD
use Illuminate\Support\Facades\Route;
=======
use Illuminate\Routing\Router;
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
<<<<<<< HEAD
     * This namespace is applied to your controller routes.
=======
     * This namespace is applied to the controller routes in your routes file.
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
<<<<<<< HEAD
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
=======
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    }

    /**
     * Define the routes for the application.
     *
<<<<<<< HEAD
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAdminRoutes();
        //
    }


    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::group([
            'prefix'=>'/admin',
            'middleware' => 'admin',
            'namespace' => 'App\Http\Controllers\Admin',
        ], function ($router) {
            require base_path('routes/admin.php');
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
=======
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/Routes/routes.php');
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
        });
    }
}
