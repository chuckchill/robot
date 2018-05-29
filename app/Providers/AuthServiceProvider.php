<?php

namespace App\Providers;

<<<<<<< HEAD
use App\Http\Requests\Request;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use League\Flysystem\Exception;
=======
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

<<<<<<< HEAD

    /**
     * Register any authentication / authorization services.
     *
=======
    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
     * @return void
     */
    public function boot(GateContract $gate)
    {
<<<<<<< HEAD
        if (!empty($_SERVER['SCRIPT_NAME']) && strtolower($_SERVER['SCRIPT_NAME']) === 'artisan') {
            return false;
        }
        $gate->before(function ($user, $ability) {
            if ($user->id === 1) {
                return true;
            }
        });
        $this->registerPolicies($gate);

        $permissions = \App\Models\Admin\Permission::with('roles')->get();

        foreach ($permissions as $permission) {
            $gate->define($permission->name, function ($user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }
    }


=======
        $this->registerPolicies($gate);

        //
    }
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
}
