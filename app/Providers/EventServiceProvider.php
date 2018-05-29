<?php

namespace App\Providers;

<<<<<<< HEAD
use Illuminate\Support\Facades\Event;
=======
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
<<<<<<< HEAD
        'App\Events\permChangeEvent' => [
            'App\Listeners\permChangeListener',
        ],
        'App\Events\userActionEvent' => [
            'App\Listeners\userActionListener',
=======
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
        ],
    ];

    /**
<<<<<<< HEAD
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
=======
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a

        //
    }
}
